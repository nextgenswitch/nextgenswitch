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
use App\Models\CallQueue;
use App\Models\SipChannel;
use App\Models\Queue;
use App\Enums\CallStatusEnum;
use App\Enums\QueueStatusEnum;
use App\Models\CallQueueExtension;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{

    public function __construct(){
        config(['menu.group' => 'menu-monitoring']);  
    } 

    


    /*public function queueStats(Request $request){

        $q       = $request->get( 'q' ) ?: '';
        // $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        // $sort    = $request->get( 'sort' ) ?: '';

        SipChannel::whereRaw( "'" . now() . "' > TIMESTAMPADD(SECOND,expire,updated_at)" )->delete();

        $stats = [
            'total_ans_calls' => 0,
            'total_aban_calls' => 0,
            'tatal_tout_calls' => 0,
            'tatal_waiting_calls' => 0,
            'total_res_time' => 0,
            'avg_res_time' => 0,
            'max_res_time' => 0,
            'avg_duration' => 0,
            'longest_duration' => 0,
            'total_duration' => 0,
        ];
        
        $callQueues = CallQueue::where('organization_id', auth()->user()->organization_id);
        
        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $callQueues->where( $filtera[0], '=', $filtera[1] );
        }

        $callQueues = $callQueues->get();


        $q       = $request->get( 'q' ) ?: '';

        $from = null;
        $to = null;

        if (  ! empty( $q ) ) {
            $q             = rtrim( $q, ',' );
            $searchColumns = explode( ',', $q );

            foreach ( $searchColumns as $searchColumn ) {
                $searchColumnArr = explode( ':', $searchColumn );

                if ( array_shift($searchColumnArr) == 'date' ) {
                    $dateArr = explode('to', implode(':', $searchColumnArr));
                    if(count($dateArr) >= 1)
                        $from = Carbon::parse(trim($dateArr[0]));
                    
                    if(count($dateArr) >= 2)
                        $to = Carbon::parse(trim($dateArr[1]));
                }

            }

        }


        
        foreach($callQueues as $queue){
            $extensionlist = CallQueueExtension::where("call_queue_id",$queue->id)->pluck('extension_id')->toArray();
            $extDstIds = Extension::whereIn('id', $extensionlist)->pluck('destination_id')->toArray();
            $totalOnlineExts = SipChannel::whereIn('sip_user_id', $extDstIds)->count();
            $queue->active_agents =  $totalOnlineExts . "/" . count($extDstIds);


            $Queue = Queue::where('call_queue_id', $queue->id);

            

            if($from && $to)
                $Queue = $Queue->where('created_at', '>=', Carbon::parse(trim($dateArr[0])))->where('created_at', '<=', Carbon::parse(trim($dateArr[1])));
            else if($from)
                $Queue = $Queue->where('created_at', '>=', Carbon::parse(trim($dateArr[0])));
            else
                $Queue->whereDate('created_at', Carbon::today());

            // Log::debug($Queue->get());
            
            $queue->total_calls = $Queue->count();

            $queue->ans_calls = $Queue->where('duration', '>', 0)->where('status', CallStatusEnum::Disconnected)->count();
            $queue->abandoned_calls = 0;        
            $queue->timeout_calls = $Queue->where('status', '>', CallStatusEnum::Disconnected)->count();

            Log::info($Queue->where('status', '>', CallStatusEnum::Disconnected)->toSql());

            $stats['total_ans_calls'] += $queue->ans_calls;
            $stats['total_aban_calls'] += $queue->abandoned_calls;
            $stats['tatal_tout_calls'] += $queue->timeout_calls;

            $stats['tatal_waiting_calls'] += $Queue->where('waiting_duration', '>', 0)->count();
            $stats['total_res_time'] += $Queue->where('waiting_duration', '>', 0)->sum('waiting_duration');


            $max_res_time_que = $Queue->orderBy('waiting_duration', 'desc')->first();
            
            if($max_res_time_que)
                $stats['max_res_time'] = $max_res_time_que->waiting_duration > $stats['max_res_time'] ? $max_res_time_que->waiting_duration : $stats['max_res_time'];

            $stats['total_duration'] += $Queue->where('duration', '>', 0)->sum('duration');

            $longest_duration_que = $Queue->orderBy('duration', 'desc')->first();
            if($longest_duration_que)
                $stats['longest_duration'] = $longest_duration_que->duration > $stats['longest_duration'] ? $longest_duration_que->duration : $stats['longest_duration'];
            
            
        }

        if($stats['tatal_waiting_calls'] > 0)
            $stats['avg_res_time'] =  duration_format($stats['total_res_time'] / $stats['tatal_waiting_calls']);

        if($stats['total_ans_calls'] > 0)
            $stats['avg_duration'] =  duration_format($stats['total_duration'] / $stats['total_ans_calls']);


        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'queue_stats.csv';

            // $callQueues = $callQueues->get();

            $headers = [
                'Content-type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $columns = ['queue_name', 'active_agents', 'answered_calls', 'abandoned_calls', 'timeout_calls', 'total_calls'];

            $callback = function () use ( $callQueues, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $callQueues as $queue ) {

                    foreach ( $columns as $column ) {

                        if ( $column == 'queue_name' ) {
                            $row[$column] = $queue->name;
                        } 

                        else if ( $column == 'answered_calls' ) {
                            $row[$column] = $queue->ans_calls;
                        } 

                        else {
                            $row[$column] = $queue->{$column};
                        }

                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $queueList = CallQueue::where('organization_id', auth()->user()->organization_id)->pluck('name', 'name');
        $view = $request->ajax() ? 'reports.queue_stats.table' : 'reports.queue_stats.index';

        return view($view, compact('callQueues', 'stats', 'queueList'));
    }
    */

    public function queueStats(Request $request)
    {
        // Sanitize input values with default fallbacks
        $query = $request->get('q', '');
        $filter = $request->get('filter', '');
        $from = null;
        $to = null;

        // Clean up expired SIP channels
        SipChannel::whereRaw("'" . now() . "' > TIMESTAMPADD(SECOND, expire, updated_at)")->delete();

        // Initialize statistics array
        $stats = [
            'total_ans_calls' => 0,
            'total_aban_calls' => 0,
            'total_tout_calls' => 0,
            'total_waiting_calls' => 0,
            'total_res_time' => 0,
            'avg_res_time' => 0,
            'max_res_time' => 0,
            'avg_duration' => 0,
            'longest_duration' => 0,
            'total_duration' => 0,
        ];

        // Build call queue base query
        $callQueues = CallQueue::where('organization_id', auth()->user()->organization_id);

        // Apply filter if provided
        if (!empty($filter)) {
            [$filterField, $filterValue] = explode(':', $filter);
            $callQueues->where($filterField, '=', $filterValue);
        }

        // Retrieve call queues
        $callQueues = $callQueues->get();

        // Parse search query for date range
        if (!empty($query)) {
            foreach (explode(',', rtrim($query, ',')) as $searchColumn) {
                if (str_starts_with($searchColumn, 'date:')) {
                    $dateArr = explode('to', str_replace('date:', '', $searchColumn));
                    $from = isset($dateArr[0]) ? Carbon::parse(trim($dateArr[0])) : null;
                    $to = isset($dateArr[1]) ? Carbon::parse(trim($dateArr[1])) : null;
                }
            }
        }

        // Process each queue to calculate statistics
        foreach ($callQueues as $queue) {
            $extensionIds = CallQueueExtension::where("call_queue_id", $queue->id)->pluck('extension_id')->toArray();
            $destinationIds = Extension::whereIn('id', $extensionIds)->pluck('destination_id')->toArray();

            // Calculate active agents
            $totalOnlineExts = SipChannel::whereIn('sip_user_id', $destinationIds)->count();
            $queue->active_agents = $totalOnlineExts . "/" . count($destinationIds);

            // Build queue records based on date filters
            $queueQuery = Queue::where('call_queue_id', $queue->id);
            if ($from && $to) {
                $queueQuery->whereBetween('created_at', [$from, $to]);
            } elseif ($from) {
                $queueQuery->where('created_at', '>=', $from);
            } else {
                $queueQuery->whereDate('created_at', Carbon::today());
            }

            // Calculate queue-specific statistics
            $queue->pending_calls = $queueQuery->clone()->where('status', '<', QueueStatusEnum::Bridged->value)->count();
            $queue->total_calls = $queueQuery->clone()->count();
            $queue->ans_calls = $queueQuery->clone()->where(function ($query) {
                $query->where('status',QueueStatusEnum::Bridged)
                      ->orWhere('status',QueueStatusEnum::Disconnected);
            })->count();
            $queue->abandoned_calls = $queueQuery->clone()->where('status', QueueStatusEnum::Abandoned)->count();  // Placeholder for abandoned call logic (custom logic might be needed here)
            $queue->timeout_calls = $queueQuery->clone()->where('status', QueueStatusEnum::Timeout)->count();
            
            // Update global statistics
            $stats['total_ans_calls'] += $queue->ans_calls;
            $stats['total_aban_calls'] += $queue->abandoned_calls;
            $stats['total_tout_calls'] += $queue->timeout_calls;
            $stats['total_waiting_calls'] += $queueQuery->clone()->where('waiting_duration', '>', 0)->count();
            $stats['total_res_time'] += $queueQuery->clone()->where('waiting_duration', '>', 0)->sum('waiting_duration');

            // Update max response time
            $maxResTimeRecord = $queueQuery->clone()->orderBy('waiting_duration', 'desc')->first();
            if ($maxResTimeRecord) {
                $stats['max_res_time'] = max($stats['max_res_time'], $maxResTimeRecord->waiting_duration);
            }

            // Update duration statistics
            $stats['total_duration'] += $queueQuery->clone()->where('duration', '>', 0)->sum('duration');
            $longestDurationRecord = $queueQuery->clone()->orderBy('duration', 'desc')->first();
            if ($longestDurationRecord) {
                $stats['longest_duration'] = max($stats['longest_duration'], $longestDurationRecord->duration);
            }
        }

        // Calculate averages
        if ($stats['total_waiting_calls'] > 0) {
            $stats['avg_res_time'] = $stats['total_res_time'] / $stats['total_waiting_calls'];
        }
        if ($stats['total_ans_calls'] > 0) {
            $stats['avg_duration'] = $stats['total_duration'] / $stats['total_ans_calls'];
        }

        // Handle CSV export request
        if ($request->has('csv')) {
            $fileName = 'queue_stats.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
            ];

            $columns = ['queue_name', 'active_agents', 'answered_calls', 'abandoned_calls', 'timeout_calls', 'total_calls'];
            $callback = function () use ($callQueues, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($callQueues as $queue) {
                    $row = [
                        'queue_name' => $queue->name,
                        'active_agents' => $queue->active_agents,
                        'answered_calls' => $queue->ans_calls,
                        'abandoned_calls' => $queue->abandoned_calls,
                        'timeout_calls' => $queue->timeout_calls,
                        'total_calls' => $queue->total_calls,
                    ];
                    fputcsv($file, $row);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // Render the view based on request type
        $queueList = CallQueue::where('organization_id', auth()->user()->organization_id)->pluck('name', 'name');
        $view = $request->ajax() ? 'reports.queue_stats.table' : 'reports.queue_stats.index';

        return view($view, compact('callQueues', 'stats', 'queueList'));
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

    public function survey(Request $request){
        $survey_id = $request->input('survey_id');
        
        if(!isset($survey_id) || empty($survey_id) || $survey_id < 1){
            
            $survey = Survey::where('organization_id', auth()->user()->organization_id)->first();

            if($survey){
                return redirect()->route('monitoring.surveys',['survey_id'=>$survey->id]);
            }

            else{
                return redirect()->route('surveys.survey.create');
            }
        }

        
        

        $survey = Survey::where('organization_id', auth()->user()->organization_id)->where('id', $survey_id)->first();

        if(!$survey){
            return redirect()->route('monitoring.surveys');
        }
        
        if($request->has('clear') && $request->input('clear') == 1){
            SurveyResult::where('survey_id', $survey_id)->delete();
            return redirect()->route('monitoring.surveys',['survey_id'=>$survey->id]);
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

        if (  ! empty( $request->get( 'csv' ) ) ) {
            $fileName       = 'survey_results.csv';
            $results = $results->get();

            $headers = [
                'Content-type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $columns = ['date', 'caller_id', 'feedback']; // specify columns if need

            $callback = function () use ( $results, $columns, $keys ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $results as $result ) {

                    foreach ( $columns as $column ) {

                        if ( $column == 'date' ) {
                            $row[$column] = optional( $result)->created_at;
                        }
                        
                        else if($column == 'feedback'){
                            if(isset($keys[$result->pressed_key])){
                                $row[$column] = $keys[$result->pressed_key];
                            }
                            else{
                                $row[$column] = "unknown({$result->pressed_key})";
                            }
                        }
                        else {
                            $row[$column] = $result->{$column};
                        }

                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }


        $results = $results->paginate($perPage);
        $results->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage, 'survey_id' => $survey_id] );

        $view = $request->ajax() ? 'reports.surveys.table' : 'reports.surveys.index';
        return view($view, compact('results', 'surveys', 'survey', 'keys'));

    }
}
