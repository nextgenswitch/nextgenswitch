<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DialerCampaign;
use App\Models\DialerCampaignCall;
use App\Models\Campaign;
use App\Enums\CallStatusEnum;
class DialerCampaignCallController extends Controller
{
    public function __construct(){
        config(['menu.group' => 'menu-campaign']);  
    } 
    public function index( Request $request) {
        
        $campaignId = $request->query('id');
        
        if(empty($campaignId) || $campaignId == 0){
            $campaign = DialerCampaign::where("organization_id",auth()->user()->organization_id)->first();

            if($campaign){
                return redirect()->route( 'dialer_campaign_calls.dialer_campaign_call.index', ['id' => $campaign->id] );
            }
        }

        $campaign = DialerCampaign::where( 'organization_id', '=', auth()->user()->organization_id )->findOrFail( $campaignId );
        $q        = $request->get( 'q' ) ?: '';
        $perPage  = $request->get( 'per_page' ) ?: 10;
        $filter   = $request->get( 'filter' ) ?: '';
        $sort     = $request->get( 'sort' ) ?: '';

        $campaignCall = DialerCampaignCall::where( 'dialer_campaign_id', $campaignId );

        if (  ! empty( $q ) ) {
            $campaignCall->where( 'tel', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $campaignCall->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $campaignCall->orderBy( $sorta[0], $sorta[1] );
        } else {
            $campaignCall->orderBy( 'created_at', 'DESC' );
        }

        

        if (  ! empty( $request->get( 'csv' ) ) ) {
            $campaignCalls = $campaignCall->get();
            

            $fileName = 'dialerCampaignCalls.csv';

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];
            
            
            // specify columns if need
            $columns = ['campaign', 'tel', 'duration', 'retry', 'status'];

            $callback = function () use ( $campaignCalls, $columns, $campaign ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $campaignCalls as $campaignCall ) {

                    foreach ( $columns as $column ) {

                        if( $column == 'campaign'){
                            $row[$column] = $campaign->name;
                        }
                        else if($column == 'status'){
                            $row[$column] = CallStatusEnum::fromKey($campaignCall->status)->getText();
                        }
                        else{
                            $row[$column] = $campaignCall->{$column};
                        }
                        
                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $campaignCalls = $campaignCall->paginate( $perPage );

        $campaignCalls->appends( ['id'=>$campaignId,'sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );


        $campaigns = DialerCampaign::where( 'organization_id', '=', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();

        if ( $request->ajax() ) {
            return view( 'dialer_campaign_calls.table', compact( 'campaignCalls', 'campaign', 'campaigns' ) );
        }
        
        return view( 'dialer_campaign_calls.index', compact( 'campaignCalls', 'campaign', 'campaigns' ) );

    }
}
