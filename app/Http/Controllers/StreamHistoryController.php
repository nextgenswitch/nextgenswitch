<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StreamHistory;
use App\Models\Stream;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StreamHistoryController extends Controller
{
    public function __construct()
    {
        config(['menu.group' => 'menu-application']);
    }

    public function index(Request $request)
    {
        $q       = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter  = $request->get('filter') ?: '';
        $sort    = $request->get('sort') ?: '';
        $date   = $request->get('date') ?: '';
        $streamHistories = StreamHistory::where('organization_id', Auth::user()->organization_id);

        if (  ! empty( $q ) ) {
            $q             = rtrim( $q, ',' );
            $searchColumns = explode( ',', $q );

            foreach ( $searchColumns as $searchColumn ) {
                $searchColumnArr = explode( ':', $searchColumn );

                if ( $searchColumnArr[0] == 'date' ) {
                    $streamHistories->whereDate( 'created_at', Carbon::parse( $searchColumnArr[1] )->format( 'Y-m-d' ) );
                    
                } else {
                    $streamHistories->where( $searchColumnArr[0], $searchColumnArr[1] );
                }

            }
        }

        if (!empty($filter)) {
            $filtera = explode(':', $filter);
            $streamHistories->where($filtera[0], '=', $filtera[1]);
        }


        if (!empty($sort)) {
            $sorta = explode(':', $sort);
            $streamHistories->orderBy($sorta[0], $sorta[1]);
        } else {
            $streamHistories->orderBy('created_at', 'DESC');
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'stream_histories.csv';
            $histories = $streamHistories->get();

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            $columns = ['caller_id','duration', 'stream', 'transcript', 'date']; // specify columns if need
            

            $callback = function () use ( $histories, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $histories as $history ) {

                    foreach ( $columns as $column ) {
                        if($column == 'stream'){
                            $row[$column] = $history->stream->name;
                        }
                        elseif($column == 'date'){
                            $row[$column] = $history->created_at;
                        }
                        elseif($column == 'duration'){
                            $row[$column] = duration_format( $history->duration );
                        }
                        else{
                            $row[$column] = $history->{$column};
                        }
                        
                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        // return $streamHistories->get();
        $streamHistories = $streamHistories->paginate($perPage);
        $streamHistories->appends(['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage]);

        $streamList = Stream::where('organization_id', Auth::user()->organization_id)
            ->pluck('name', 'id')
            ->toArray();    

        if ($request->ajax()) {
            return view('monitoring.stream_histories.table', compact('streamHistories', 'streamList'));
        }
        return view('monitoring.stream_histories.index', compact('streamHistories', 'streamList'));
    }
}
