<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignSms;
use App\Models\Func;
use Illuminate\Http\Request;

class CampaignSmsController extends Controller
{
    /**
     * Display a listing of the campaign calls.
     *
     * @return Illuminate\View\View
     */
    public function index( Request $request, Int $campaignId ) {
            
        $campaign = Campaign::where( 'organization_id', '=', auth()->user()->organization_id )->findOrFail( $campaignId );
        $q        = $request->get( 'q' ) ?: '';
        $perPage  = $request->get( 'per_page' ) ?: 10;
        $filter   = $request->get( 'filter' ) ?: '';
        $sort     = $request->get( 'sort' ) ?: '';

        $campaignSms = CampaignSms::with( ['campaign', 'smsHistroy'] )->where( 'campaign_id', '=', $campaignId );

        if (  ! empty( $q ) ) {
            $campaignSms->where( 'contact', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $campaignSms->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $campaignSms->orderBy( $sorta[0], $sorta[1] );
        } else {
            $campaignSms->orderBy( 'created_at', 'DESC' );
        }

        
        /*
        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'campaignsms.csv';
            $campaignSms = $campaignSms->get();

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            // specify columns if need
            $columns = ['contact', 'content', 'status'];

            $callback = function () use ( $campaignSms, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $campaignSms as $sms ) {

                    foreach ( $columns as $column ) {
                        $row[$column] = $sms->{$column};
                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        */

        $campaignSms = $campaignSms->paginate( $perPage );

        $campaignSms->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );



        $campaigns = Campaign::where( 'organization_id', '=', auth()->user()->organization_id )
        ->where( function($q){
            $func = Func::where('func', 'sms')->first();
            return $q->where('function_id', $func->id);
        })
        ->pluck( 'name', 'id' )->all();


        if ( $request->ajax() ) {
            return view( 'campaign_sms.table', compact( 'campaignSms', 'campaign', 'campaigns' ) );
        }

        return view( 'campaign_sms.index', compact( 'campaignSms', 'campaign', 'campaigns' ) );

    }
}
