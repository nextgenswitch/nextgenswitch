<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\VoiceMail;
use App\Models\VoiceRecord;
use Illuminate\Http\Request;

class VoiceMailController extends Controller
{
    public function __construct(){
        config(['menu.group' => 'menu-monitoring']);  
    }
    
    public function index(Request $request){
        
        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';

        $mails = VoiceMail::with('voiceRecord')->where('organization_id', auth()->user()->organization_id);
        
        

        if (  ! empty( $q ) ) {
            $q             = rtrim( $q, ',' );
            $searchColumns = explode( ',', $q );

            foreach ( $searchColumns as $searchColumn ) {
                $searchColumnArr = explode( ':', $searchColumn );

                if ( $searchColumnArr[0] == 'date' ) {
                    $mails->whereDate( 'created_at', Carbon::parse( $searchColumnArr[1] )->format( 'Y-m-d' ) );
                    
                } else {
                    $mails->where( $searchColumnArr[0], $searchColumnArr[1] );
                }

            }
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $mails->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $mails->orderBy( $sorta[0], $sorta[1] );
        } else {
            $mails->orderBy( 'created_at', 'DESC' );
        }

        

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'voice_mails.csv';
            $mails = $mails->get();

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            $columns = ['extension_name','caller_id','transcript', 'date']; // specify columns if need
            

            $callback = function () use ( $mails, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $mails as $mail ) {

                    foreach ( $columns as $column ) {
                        if($column == 'extension_name'){
                            $row[$column] = $mail->extension->name;
                        }
                        elseif($column == 'date'){
                            $row[$column] = $mail->created_at;
                        }
                        else{
                            $row[$column] = $mail->{$column};
                        }
                        
                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        
        $mails = $mails->paginate( $perPage );

        $mails->appends( ['sort' => $sort, 'filter' => $filter, 'per_page' => $perPage] );


        $view = $request->ajax() ? 'monitoring.voice_mails.table' : 'monitoring.voice_mails.index';

        $voiceRecordProfiles = VoiceRecord::where('organization_id', auth()->user()->organization_id)->pluck('name', 'id');
        return view($view, compact('mails','voiceRecordProfiles'));
    }


    public function preview( $id ) {

        $mail = VoiceMail::find($id);

        if ( $mail ) {
            $path = asset( 'storage/' . $mail->voice_path );

            return response()->json( ['status' => true, 'path' => $path] );
        }

        return response()->json( ['status' => false, 'path' => null] );
    }


}
