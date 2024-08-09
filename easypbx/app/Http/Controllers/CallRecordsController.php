<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\CallRecord;
use App\Models\DialCall;
use App\Models\Organization;
use Illuminate\Http\Request;
use Exception;
use Schema;

class CallRecordsController extends Controller
{

    /**
     * Display a listing of the call records.
     *
     * @return Illuminate\View\View
     */
    public function index(Request $request, $call_id)
    {
  
        $q = $request->get('q') ?: '';
        $perPage = $request->get('per_page') ?: 10;
        $filter = $request->get('filter') ?: '';
        $sort = $request->get('sort') ?: '';
        $callRecord = CallRecord::with(['call', 'dialCall'])->where('organization_id', auth()->user()->organization_id)->where('call_id', $call_id);
        

        if(!empty($filter)){
            $filtera = explode(':',$filter);
            $callRecord->where($filtera[0], '=',$filtera[1]);
        }

        if(!empty($sort)){
            $sorta = explode(':',$sort);
            $callRecord->orderBy($sorta[0],$sorta[1]);
        }else
            $callRecord->orderBy('created_at','DESC'); 
        




        $callRecords = $callRecord->paginate($perPage);
        
        $callRecords->appends(['sort'=>$sort,'filter'=>$filter,'q' => $q,'per_page'=>$perPage]); 

        if(!empty($request->get('csv'))){

            $fileName = 'callRecords.csv';
         

                $headers = array(
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );


                //$column = ['name','email','password']; // specify columns if need
                $columns = Schema::getColumnListing((new CallRecord)->getTable());  
               
                $callback = function() use($callRecords, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($callRecords as $callRecord) {
            

                        //$row['Title']  = $task->title;
                        
                        foreach($columns as $column)
                             $row[$column] = $callRecord->{$column};

                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
        }

       

        if($request->ajax()){
            return view('call_records.table', compact('callRecords'));
        } 
        
        return view('call_records.index', compact('callRecords'));


    }

    public function preview( $id ) {

        $record = CallRecord::where( 'call_id', $id )->first();

        if ( $record ) {
            $path = asset( 'storage/' . $record->record_path );

            return response()->json( ['status' => true, 'path' => $path] );
        }

        return response()->json( ['status' => false, 'path' => null] );
    }


    

}
