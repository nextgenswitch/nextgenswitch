<?php

namespace App\Http\Controllers\Api\Functions;

use App\Enums\CallStatusEnum;
use App\Http\Controllers\Api\FunctionCall;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessCampaign;
use App\Models\Campaign;
use App\Models\CampaignCall;
use App\Models\CampaignSms;
use App\Models\Contact;
use App\Models\Func;
use App\Models\Sms;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class CampaignWorker extends Controller {
    protected $campaign;
    protected $contactsForCalls = [];
    protected $is_sms_campaign  = false;

    public function __construct( int $campaignId ) {
        $this->campaign = Campaign::find( $campaignId );

        if ( $this->campaign->call_limit == 0 ) {
            $this->campaign->call_limit = 100000;
        }

        $this->isSMS( $this->campaign );

    }

    public function process(): void {
        Log::debug( 'Campaign job is running ' . $this->campaign->id );

        if ( $this->campaignInTime( $this->campaign ) && $this->campaign->status == 1 && ! empty( $this->campaign->contact_groups ) ) {
            $campaignContacts = $this->getCampaignContacts( $this->campaign );
            $contactsForCalls = $this->getContactsForCalls( $campaignContacts, $this->campaign );

            //Log::debug( '----------------Contacts for call-----------------' );
            //Log::debug( $contactsForCalls );

            if ( count( $contactsForCalls ) ) {

                if ( $this->is_sms_campaign ) {
                    $this->sendSMS( $contactsForCalls, $this->campaign );
                } else {
                    $this->sendCalls( $contactsForCalls, $this->campaign );
                }

            } else {
                // process retry cotnact
                $retryContacts = $this->getRetryContacts( $this->campaign );

                Log::debug( '----------------retry contacts-----------------' );
                Log::debug( $retryContacts );

                if ( count( $retryContacts ) ) {
                    $this->sendCalls( $retryContacts, $this->campaign );
                }

            }

            if ( $this->isCompletedCampaign( $this->campaign, $contactsForCalls ) ) {

                $data = [
                    'status' => CallStatusEnum::Disconnected->value,
                ];

                if ( $this->is_sms_campaign ) {
                    $campaignSms        = CampaignSms::where( 'campaign_id', $this->campaign->id );
                    $data['total_sent'] = $campaignSms->count();
                } else {
                    $campaignCalls             = CampaignCall::where( 'campaign_id', $this->campaign->id );
                    $data['total_sent']        = $campaignCalls->count();
                    $data['total_successfull'] = $campaignCalls->where( 'status', '=', CallStatusEnum::Disconnected->value )->count();
                    $data['total_failed']      = $data['total_sent'] - $data['total_successfull'];
                }

                $this->campaign->update( $data );

                Log::debug( 'Campaign is completed' );
            }

        }

        if ( $this->shouldRerunCampaign( $this->campaign ) ) {
            $processCampaign = new ProcessCampaign( $this->campaign->id );
			
			if($this->campaignInTime($this->campaign))
				dispatch( $processCampaign )->delay( now()->addSeconds( 10 ) ); //addMinutes addSeconds
			else
				dispatch( $processCampaign )->delay( now()->addMinutes( 1 ) ); //addMinutes addSeconds
			
			
            Log::debug( 'Campaign job in queue' );
        }

    }

    public function campaignInTime( Campaign $campaign ) {
        $inTime = $this->inTime( $campaign->start_at, $campaign->end_at, $campaign->timezone, $campaign->schedule_days );

        if (  ! $inTime ) {
            Log::debug( "The current time does not fall within the scheduled campaign#{$campaign->id} time." );
        }

        return $inTime;
    }

    public function inTime( $start, $end, $timezone, $days = null ): bool {
        $convertedDateTime = now()->tz( $timezone );

        $currentDay = $convertedDateTime->format( 'D' );

        $inTime = $convertedDateTime->between( $start, $end );

        if ( $inTime && $days != null ) {
            $inTime = in_array( strtolower( $currentDay ), $days );
        }

        return $inTime;
    }

    public function getCampaignContacts( $campaign ): array | object {
        $contacts = Contact::select( [DB::raw( "CONCAT(COALESCE(cc, ''),tel_no) as tel" )] );

        foreach ( $campaign->contact_groups as $key => $groupId ) {
            $statement = $key === 0 ? 'whereRaw' : 'orWhereRaw';
            $contacts->{$statement}

            ( 'FIND_IN_SET(?, contact_groups)', [$groupId] );
        }

        return $contacts
            ->groupBy( 'tel' )
            ->pluck( 'tel' )
            ->toArray();
    }

    public function getRetryContacts( $campaign ) {

        if ( $this->is_sms_campaign ) {
            return [];
        }

        CampaignCall::where( 'campaign_id', $campaign->id )
                    ->where('status','<',CallStatusEnum::Disconnected->value)
                    ->whereRaw('TIMESTAMPDIFF(MINUTE, updated_at, now())  > 2')
                    ->update(['status'=>CallStatusEnum::Failed->value]);

        return CampaignCall::where( 'campaign_id', $campaign->id )
            ->where( 'retry', '<', $campaign->max_retry )
            ->where( 'status', '>', CallStatusEnum::Disconnected->value )
            ->limit( $campaign->call_limit )
            ->pluck( 'tel' )
            ->toArray();
    }

    public function getContactsForCalls( array $contacts, Campaign $campaign ): array {

        if ( $this->is_sms_campaign ) {
            $campaignContacts = CampaignSms::where( 'campaign_id', $campaign->id )
                ->pluck( 'contact' )
                ->toArray();
        } else {
            $campaignContacts = CampaignCall::where( 'campaign_id', $campaign->id )
                ->pluck( 'tel' )
                ->toArray();
        }

        return array_values( array_diff( $contacts, $campaignContacts ) );
    }

    public function sendCalls( $contactList, Campaign $campaign ) {
        $destination = [
            'func_id' => $campaign->function_id,
            'dest_id' => $campaign->destination_id,
        ];

        $payload = [
            'from'            => $campaign->from,
            'organization_id' => $campaign->organization_id,
            'response'        => route( 'api.func_call', $destination ),
        ];
		
		//active call
		$limit = ($this->campaign->call_limit) - $this->totalActiveCall();

        for ( $send = 0; $send < $limit; $send++ ) {

            if ( count( $contactList ) <= $send ) {
                break;
            }

            $payload['to'] = $contactList[$send];
            //Log::debug( '--------------Playload--------------' );
            //Log::info( $payload );
            $payload['statusCallback'] = route('campaign.history.update', $campaign->id);

            $callResponse = FunctionCall::send_call( $payload );
            //Log::debug( '--------------Sent Call Response--------------' );
            //Log::info( $callResponse );

            // update or insert call record
            $this->callLog( $this->campaign, $contactList[$send], $callResponse );

            $wsRes = [
                'date' => date('d-m-Y H:i:s'),
                'contact' => $contactList[$send],
                'status' => ''
            ];      

            if(isset($callResponse['error']) && $callResponse['error'] ){
                $wsRes['status'] = $callResponse['error_message'];
            }

            if( isset($callResponse['call_id']) ){
                $wsRes['status'] = $callResponse['status'];
            }   
            
            FunctionCall::send_to_websocket('campaign_' . $this->campaign->id, $wsRes);
        }

    }

	
	public function totalActiveCall(){
		return CampaignCall::where( 'status', '<', CallStatusEnum::Disconnected->value )->count();
	}
	
	
    public function sendSMS( $contactList, Campaign $campaign ) {
        $sms = Sms::find( $campaign->destination_id );

        $payload = [
            'organization_id' => $campaign->organization_id,
            'from'            => $campaign->from,
            'body'            => $sms->content,
            'sms_count'       => $sms->sms_count,
        ];

        for ( $send = 0; $send < $this->campaign->call_limit; $send++ ) {

            if ( count( $contactList ) <= $send ) {
                break;
            }

            $payload['to'] = $contactList[$send];
            $smsResponse   = FunctionCall::send_sms( $payload );

            $this->smsLog( $this->campaign, $contactList[$send], $smsResponse );

            $wsRes = [
                'date' => date('d-m-Y H:i:s'),
                'contact' => $contactList[$send],
                'status' => $smsResponse->status
            ];

            FunctionCall::send_to_websocket('campaign_' . $this->campaign->id, $wsRes);

        }

    }

    public function callLog( Campaign $campaign, $tel, $callResponse ) {
        $call = [
            'campaign_id' => $campaign->id,
            'tel'         => $tel,
            'status'      => isset( $callResponse['status-code'] ) ? $callResponse['status-code'] : CallStatusEnum::Failed->value,
            'retry'       => 1,
        ];

        if ( isset( $callResponse['call_id'] ) ) {
            $call['call_id'] = $callResponse['call_id'];
        }

        $campaignCall = CampaignCall::where( 'campaign_id', $campaign->id )
            ->where( 'tel', $tel )
            ->first();

        if ( $campaignCall ) {
            $call['retry'] = $campaignCall->retry + 1;
            $campaignCall->update( $call );
        } else {
            CampaignCall::create( $call );
        }

        //Log::debug( '----------Campaigncall update into database------------------' );
        //Log::debug( $call );
    }

    public function smsLog( Campaign $campaign, $contact, $callResponse ) {
        $data = [
            'campaign_id'    => $campaign->id,
            'sms_history_id' => $callResponse['id'],
            'contact' => $contact,
        ];

        CampaignSms::create( $data );

    }

    public function statusUnderDisconnect() {
        return CampaignCall::where( 'status', '<', CallStatusEnum::Disconnected->value )->exists();
    }

    public function isCompletedCampaign( Campaign $campaign, array $contactsForCalls ): bool {

        if ( $this->is_sms_campaign ) {
            return count( $contactsForCalls ) <= $campaign->call_limit;
        } elseif (  ! $this->statusUnderDisconnect() && count( $this->getRetryContacts( $campaign ) ) == 0 && count( $contactsForCalls ) <= $campaign->call_limit ) {
            return true;
        }

        return false;
    }

    public function shouldRerunCampaign( Campaign $campaign ): bool {

        $campaignContacts = $this->getCampaignContacts( $this->campaign );
        $contactsForCalls = $this->getContactsForCalls( $campaignContacts, $this->campaign );

        if ( $this->is_sms_campaign && $this->campaign->status == 1 && count( $contactsForCalls ) > 0 ) {
            return true;
        } elseif ( $this->campaign->status == 1 && ( $this->statusUnderDisconnect() || count( $this->getRetryContacts( $campaign ) ) || count( $contactsForCalls ) ) ) {
            return true;
        }

        return false;
    }

    public function isSMS( Campaign $campaign ) {
        $function = Func::find( $campaign->function_id );

        $this->is_sms_campaign = $function->func == 'sms' ? true : false;
    }

}
