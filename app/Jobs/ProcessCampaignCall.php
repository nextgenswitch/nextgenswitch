<?php

namespace App\Jobs;

use App\Enums\CallStatusEnum;
use App\Models\Campaign;
use App\Models\CampaignCall;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessCampaignCall implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $campaign;

    /**
     * Create a new job instance.
     */
    public function __construct( int $campaignId ) {
        $this->campaign = Campaign::find( $campaignId );
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        Log::debug( 'CampaignCall Job is running.' );

        $campaignCalls = $this->getCampaignCalls( $this->campaign );
        Log::debug( $campaignCalls );

        foreach ( $campaignCalls as $campaignCall ) {

            $data['status'] = $campaignCall->call->status;

            if ( $campaignCall->call->duration > 0 ) {
                $data['completed'] = 1;
                $data['duration']  = $campaignCall->call->duration;
                
            } else

            if ( $campaignCall->retry == $this->campaign->max_retry && $campaignCall->call->status->value >= CallStatusEnum::Disconnected->value ) {
                $data['completed'] = 1;
            }

            CampaignCall::find( $campaignCall->id )->update( $data );
        }

        if ( count( $this->getCampaignCalls( $this->campaign ) ) || $this->existsRetryContacts( $this->campaign ) ) {
            dispatch( new ProcessCampaignCall( $this->campaign->id ) )->delay( now()->addMinutes( 1 ) );
            Log::debug( 'CampaignCall Job in queue.' );
        } else {
            Log::debug( 'CampaignCall Job is Completed' );
        }

    }

    public function getCampaignCalls( Campaign $campaign ): object {

        return CampaignCall::where( 'campaign_id', $campaign->id )
            ->whereNotNull( 'call_id' )
            ->where( 'completed', 0 )
            ->where( 'status', '<=', CallStatusEnum::Disconnected->value )
            ->get();
    }

    public function existsRetryContacts( Campaign $campaign ): bool {
        return CampaignCall::where( 'campaign_id', $campaign->id )
            ->where( 'completed', 0 )
            ->where( 'status', '>=', CallStatusEnum::Disconnected->value )
            ->where( 'retry', '<', $campaign->max_retry )
            ->exists();

    }

}
