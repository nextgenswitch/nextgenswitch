<?php

namespace App\Http\Controllers\Api\Functions;

use App\Enums\CallStatusEnum;
use App\Http\Controllers\Api\FunctionCall;
use App\Jobs\ProcessBroadcast;
use App\Models\Campaign;
use App\Models\CampaignCall;
use App\Models\CampaignSms;
use App\Models\Contact;
use App\Models\Func;
use App\Models\Sms;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class BroadcastWorker  {
    protected $campaign;
    protected $contactsForCalls = [];
    protected $is_sms_campaign  = false;

    public function __construct( int $campaignId ) {
        $this->campaign = Campaign::find( $campaignId );
    }

    public function process(): void {
        Log::debug( 'Campaign job is running ' . $this->campaign->id );
        if($this->campaign->status != 1) return;

        $finished = false;   
        if ( Campaign::inTime( $this->campaign ) ) {
            $contactsForCalls =  Campaign::getCallContacts($this->campaign);  

            if ( count( $contactsForCalls ) ) {
                $this->sendCalls( $contactsForCalls);                
            } else {
                // process retry cotnact
                $retryContacts = CampaignCall::getRetryCoctacts( $this->campaign );

        

                if ( count( $retryContacts ) ) {
                    $this->sendCalls( $retryContacts);
                }else{
                    if(CampaignCall::where( 'campaign_id', $this->campaign->id )->where('status', '<', CallStatusEnum::Disconnected->value )->count() ==0)
                        $finished = true;
                }
                    
            }


            $data = [];
            $campaignCalls             = CampaignCall::where( 'campaign_id', $this->campaign->id );
            $data['total_sent']        = $campaignCalls->count();            
            $data['total_successfull'] = $campaignCalls->where( 'status', '=', CallStatusEnum::Disconnected->value )->count();
            if($finished) $data['status'] = 3;
            
            

           /*  if (!$finished )  {
                $processBroadcast = new ProcessBroadcast( $this->campaign->id );	
                Log::debug( 'Campaign job going queue' );
                dispatch(new ProcessBroadcast( $this->campaign->id ))->delay( now()->addMinutes( 1 )); 
                $data['on_queue'] = true;
            } */

            $this->campaign->update( $data );

        }

       

    }



    public function sendCalls( $contactList) {
        if($this->isSMS()){
            $this->sendSMS($contactList);
            return;
        }

        $destination = [
            'func_id' => $this->campaign->function_id,
            'dest_id' => $this->campaign->destination_id,
        ];

        $payload = [
            'from'            => Contact::sanitize_phone($this->campaign->from), //preg_replace('/\D+/', '', $this->campaign->from),
            'organization_id' => $this->campaign->organization_id,
            'response'        => route( 'api.func_call', $destination ),
        ];
		
		//active call
		$limit = $this->campaign->call_limit - CampaignCall::activeCallCount($this->campaign);

        for ( $send = 0; $send < $limit; $send++ ) {

            if ( count( $contactList ) <= $send ) {
                break;
            }

            $payload['to'] = Contact::sanitize_phone($contactList[$send]); //preg_replace('/\D+/', '', $contactList[$send]);
            //Log::debug( '--------------Playload--------------' );
            //Log::info( $payload );
            $payload['statusCallback'] = route('broadcast.history.update', $this->campaign->id);

            $callResponse = FunctionCall::send_call( $payload );
            //Log::debug( '--------------Sent Call Response--------------' );
            //Log::info( $callResponse );

            // update or insert call record
            $this->callLog( $contactList[$send], $callResponse );

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

	

	
    public function sendSMS( $contactList ) {
        $sms = Sms::find( $this->campaign->destination_id );

        $payload = [
            'organization_id' => $this->campaign->organization_id,
            'from'            => $this->campaign->from,
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

    public function callLog(  $tel, $callResponse ) {
        $call = [
            'campaign_id' => $this->campaign->id,
            'tel'         => $tel,
            'status'      => isset( $callResponse['status-code'] ) ? $callResponse['status-code'] : CallStatusEnum::Failed->value,
            'retry'       => 1,
        ];

        if ( isset( $callResponse['call_id'] ) ) {
            $call['call_id'] = $callResponse['call_id'];
        }

        $campaignCall = CampaignCall::where( 'campaign_id', $this->campaign->id )
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

    public function smsLog(  $contact, $smsResponse ) {
        $data = [
            'campaign_id'    => $this->campaign->id,
            'sms_history_id' => $smsResponse['sms_history_id'],
            'tel' => $contact,
            'status'=> ($smsResponse['success'])?CallStatusEnum::Disconnected:CallStatusEnum::Failed,
           
        ];

        $campaignCall = CampaignCall::where( 'campaign_id', $this->campaign->id )
            ->where( 'tel', $contact )
            ->first();

        if ( $campaignCall ) {
            $data['retry'] = $campaignCall->retry + 1;
            $campaignCall->update( $data );
        } else {
            CampaignCall::create( $data );
        }    

    }

    public function isSMS() {
        $function = Func::find( $this->campaign->function_id );
        $this->is_sms_campaign = $function->func == 'sms' ? true : false;
    }

}
