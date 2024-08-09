<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Call;
use App\Models\Campaign;
use App\Models\Survey;
use App\Models\Extension;
use App\Models\SurveyResult;
use App\Models\Trunk;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function __construct(){
        config(['menu.group' => 'menu-reports']);  
    } 
    public function extensionSummery(Request $request){
        $extDstIds = Extension::where( 'organization_id', auth()->user()->organization_id )->where('extension_type', '1')->pluck('destination_id')->toArray();
        
        $inCalls = Call::where('organization_id', auth()->user()->organization_id)->whereIn('sip_user_id', $extDstIds)->where('uas', 1)->groupBy('sip_user_id');
        $outCalls = Call::where('organization_id', auth()->user()->organization_id)->whereIn('sip_user_id', $extDstIds)->where('uas', 0)->groupBy('sip_user_id');

        $q       = $request->get( 'q' ) ?: '';

        if (  ! empty( $q ) ) {
            $q             = rtrim( $q, ',' );
            $searchColumns = explode( ',', $q );

            
            foreach ( $searchColumns as $searchColumn ) {
                $searchColumnArr = explode( ':', $searchColumn );

                if ( array_shift($searchColumnArr) == 'date' ) {
                    $dateArr = explode('to', implode(':', $searchColumnArr));
                    //return $dateArr;

                    if(count($dateArr) >= 1){
                        $inCalls = $inCalls->where('created_at', '>=', Carbon::parse(trim($dateArr[0])));
                        $outCalls = $outCalls->where('created_at', '>=', Carbon::parse(trim($dateArr[0])));
                        // return trim($dateArr[0]);
                    }
                    else{
                        $inCalls = $inCalls->whereDate('created_at', Carbon::today());
                        $outCalls = $outCalls->whereDate('created_at', Carbon::today());
                    }
                   

                    if(count($dateArr) >= 2){
                        
                        $inCalls = $inCalls->where('created_at', '<=', Carbon::parse(trim($dateArr[1])));
                        $outCalls = $outCalls->where('created_at', '<=', Carbon::parse(trim($dateArr[1])));

                    }   
                    else{
                        $inCalls = $inCalls->whereDate('created_at', Carbon::today());
                        $outCalls = $outCalls->whereDate('created_at', Carbon::today());
                    }                 

                }

            }

        }
        
        else{
            $inCalls = $inCalls->whereDate('created_at', Carbon::today());
            $outCalls = $outCalls->whereDate('created_at', Carbon::today());
        }
        
        $inCalls = $inCalls->selectRaw('sip_user_id, SUM(duration) as duration');
        $inCalls = $inCalls->selectRaw('COUNT(CASE WHEN status = 3 THEN 1 END) as success');
        $inCalls = $inCalls->selectRaw('COUNT(CASE WHEN status > 3 THEN 1 END) as failed');
        $inCalls = $inCalls->get();

        
        // $outCalls = $outCalls->whereDate('created_at', Carbon::today());
        $outCalls = $outCalls->selectRaw('sip_user_id, SUM(duration) as duration');
        $outCalls = $outCalls->selectRaw('COUNT(CASE WHEN status = 3 THEN 1 END) as success');
        $outCalls = $outCalls->selectRaw('COUNT(CASE WHEN status > 3 THEN 1 END) as failed');
        $outCalls = $outCalls->get();

        if (  ! empty( $request->get( 'csv' ) ) ) {
            $fileName = 'extension_summary.csv';

            $calls    = count($inCalls) > count($outCalls) ? $inCalls : $outCalls;

            $headers  = [
                'Content-type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $columns = ['name', 'duration(incall)', 'success(incall)', 'failed(incall)', 'duration(outcall)', 'success(outcall)', 'failed(outcall)'];

            $callback = function () use ( $calls, $columns, $inCalls, $outCalls ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $calls as $key => $call ) {
                    $extension = isset($call->sipUser->extension) ? $call->sipUser->extension : null;
                    $row['name'] = optional($extension)->name;

                    $row['duration(incall)'] = isset($inCalls[$key]) ? $inCalls[$key]->human_readable_duration : '';
                    $row['success(incall)'] = isset($inCalls[$key]) ? $inCalls[$key]->success : '';
                    $row['failed(incall)'] = isset($inCalls[$key]) ? $inCalls[$key]->failed : '';

                    $row['duration(outcall)'] = isset($outCalls[$key]) ? $outCalls[$key]->human_readable_duration : '';
                    $row['success(outcall)'] = isset($outCalls[$key]) ? $outCalls[$key]->success : '';
                    $row['failed(outcall)'] = isset($outCalls[$key]) ? $outCalls[$key]->failed : '';

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }


        $view = $request->ajax() ? 'reports.ext_call_summeries.table' : 'reports.ext_call_summeries.index';

        return view($view, compact('inCalls', 'outCalls'));

        
    }


    public function trunkSummery(Request $request){
        $truckSipUserIds = Trunk::where( 'organization_id', auth()->user()->organization_id )->pluck('sip_user_id')->toArray();
        

        $inCalls = Call::where('organization_id', auth()->user()->organization_id)->whereIn('sip_user_id', $truckSipUserIds)->where('uas', 1)->groupBy('sip_user_id');
        
        

        $outCalls = Call::where('organization_id', auth()->user()->organization_id)->whereIn('sip_user_id', $truckSipUserIds)->where('uas', 0)->groupBy('sip_user_id');

        $q       = $request->get( 'q' ) ?: '';

        if (  ! empty( $q ) ) {
            $q             = rtrim( $q, ',' );
            $searchColumns = explode( ',', $q );

            
            foreach ( $searchColumns as $searchColumn ) {
                $searchColumnArr = explode( ':', $searchColumn );

                if ( array_shift($searchColumnArr) == 'date' ) {
                    $dateArr = explode('to', implode(':', $searchColumnArr));
                    //return $dateArr;

                    if(count($dateArr) >= 1){
                        $inCalls = $inCalls->where('created_at', '>=', Carbon::parse(trim($dateArr[0])));
                        $outCalls = $outCalls->where('created_at', '>=', Carbon::parse(trim($dateArr[0])));
                        // return trim($dateArr[0]);
                    }
                    else{
                        $inCalls = $inCalls->whereDate('created_at', Carbon::today());
                        $outCalls = $outCalls->whereDate('created_at', Carbon::today());
                    }
                   

                    if(count($dateArr) >= 2){
                        
                        $inCalls = $inCalls->where('created_at', '<=', Carbon::parse(trim($dateArr[1])));
                        $outCalls = $outCalls->where('created_at', '<=', Carbon::parse(trim($dateArr[1])));

                    }   
                    else{
                        $inCalls = $inCalls->whereDate('created_at', Carbon::today());
                        $outCalls = $outCalls->whereDate('created_at', Carbon::today());
                    }                 

                }

            }

        }
        
        else{
            $inCalls = $inCalls->whereDate('created_at', Carbon::today());
            $outCalls = $outCalls->whereDate('created_at', Carbon::today());
        }


        
        $inCalls = $inCalls->selectRaw('sip_user_id, SUM(duration) as duration');

        $inCalls = $inCalls->selectRaw('COUNT(CASE WHEN status = 3 THEN 1 END) as success');

        $inCalls = $inCalls->selectRaw('COUNT(CASE WHEN status > 3 THEN 1 END) as failed');

        $inCalls = $inCalls->get();
        

        
        // $outCalls = $outCalls->whereDate('created_at', Carbon::today());
        $outCalls = $outCalls->selectRaw('sip_user_id, SUM(duration) as duration');
        $outCalls = $outCalls->selectRaw('COUNT(CASE WHEN status = 3 THEN 1 END) as success');
        $outCalls = $outCalls->selectRaw('COUNT(CASE WHEN status > 3 THEN 1 END) as failed');
        $outCalls = $outCalls->get();


        if (  ! empty( $request->get( 'csv' ) ) ) {
            $fileName = 'trunk_summary.csv';

            $calls    = count($inCalls) > count($outCalls) ? $inCalls : $outCalls;

            $headers  = [
                'Content-type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $columns = ['name', 'duration(incall)', 'success(incall)', 'failed(incall)', 'duration(outcall)', 'success(outcall)', 'failed(outcall)'];

            $callback = function () use ( $calls, $columns, $inCalls, $outCalls ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $calls as $key => $call ) {
                    $trunk = isset($call->sipUser->trunk) ? $call->sipUser->trunk : null;
                    $row['name'] = optional($trunk)->name;

                    $row['duration(incall)'] = isset($inCalls[$key]) ? $inCalls[$key]->human_readable_duration : '';
                    $row['success(incall)'] = isset($inCalls[$key]) ? $inCalls[$key]->success : '';
                    $row['failed(incall)'] = isset($inCalls[$key]) ? $inCalls[$key]->failed : '';

                    $row['duration(outcall)'] = isset($outCalls[$key]) ? $outCalls[$key]->human_readable_duration : '';
                    $row['success(outcall)'] = isset($outCalls[$key]) ? $outCalls[$key]->success : '';
                    $row['failed(outcall)'] = isset($outCalls[$key]) ? $outCalls[$key]->failed : '';

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }


        $view = $request->ajax() ? 'reports.trunk_call_summeries.table' : 'reports.trunk_call_summeries.index';

        return view($view, compact('inCalls', 'outCalls'));

        
    }


    public function campaign(Request $request){
        $campaigns = Campaign::where('organization_id', auth()->user()->organization_id);

        $q       = $request->get( 'q' ) ?: '';

        if (  ! empty( $q ) ) {

            $q             = rtrim( $q, ',' );
            $searchColumns = explode( ',', $q );

            
            foreach ( $searchColumns as $searchColumn ) {
                $searchColumnArr = explode( ':', $searchColumn );

                if ( array_shift($searchColumnArr) == 'date' ) {
                    $dateArr = explode('to', implode(':', $searchColumnArr));
                    

                    if(count($dateArr) >= 1){
                        $campaigns = $campaigns->where('created_at', '>=', Carbon::parse(trim($dateArr[0])));
                    }
                    else{
                        $campaigns = $campaigns->whereDate('created_at', Carbon::today());
                    }
                   

                    if(count($dateArr) >= 2){
                        $campaigns = $campaigns->where('created_at', '<=', Carbon::parse(trim($dateArr[1])));

                    }   
                    else{
                        $campaigns = $campaigns->whereDate('created_at', Carbon::today());
                    }                 

                }

            }

        }
        else{
            $campaigns = $campaigns->whereDate('created_at', Carbon::today());
        }

        

        if (  ! empty( $request->get( 'csv' ) ) ) {
            $fileName       = 'campaign_reports.csv';
            $campaigns = $campaigns->get();

            $headers = [
                'Content-type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $columns = ['date', 'name', 'total_sent', 'total_successfull', 'total_failed']; // specify columns if need

            $callback = function () use ( $campaigns, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $campaigns as $campaign ) {

                    foreach ( $columns as $column ) {

                        if ( $column == 'date' ) {
                            $row[$column] = optional( $campaign)->created_at;
                        }else {
                            $row[$column] = $campaign->{$column};
                        }

                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $campaigns = $campaigns->get();

        $view = $request->ajax() ? 'reports.campaigns.table' : 'reports.campaigns.index';
        
        return view($view, compact('campaigns'));
    }

    public function survey(Request $request, $survey_id = 0){

        if(!isset($survey_id) || empty($survey_id) || $survey_id < 1){
            $survey = Survey::where('organization_id', auth()->user()->organization_id)->first();

            if($survey){
                return redirect()->route('report.surveys', $survey->id);
            }

            else{
                return redirect()->route('surveys.survey.create');
            }
        }

        $survey = Survey::where('organization_id', auth()->user()->organization_id)->where('id', $survey_id)->first();

        if(!$survey){
            return redirect()->route('report.surveys', 0);
        }

        $keys = array();

        if( isset($survey->keys) ){
            $temp = json_decode($survey->keys);
            
            if(json_last_error() === JSON_ERROR_NONE){
                foreach($temp as $k => $val){
                    $keys[$val->key] = $val->text;
                }    
            }
            
        }

        
        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';


        $surveys = Survey::where('organization_id', auth()->user()->organization_id)->pluck('name', 'id');
        $results = SurveyResult::where('survey_id', $survey_id);


        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $results->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $results->orderBy( $sorta[0], $sorta[1] );
        } else {
            $results->orderBy( 'created_at', 'DESC' );
        }

        $results = $results->paginate($perPage);

        $view = $request->ajax() ? 'reports.surveys.table' : 'reports.surveys.index';
        return view($view, compact('results', 'surveys', 'survey', 'keys'));

    }
}
