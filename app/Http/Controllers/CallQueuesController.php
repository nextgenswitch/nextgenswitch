<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\FuncTrait;
use App\Models\CallQueue;
use App\Models\CallQueueExtension;
use App\Models\Extension;
use App\Models\Func;
use App\Models\VoiceFile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Schema;

class CallQueuesController extends Controller {

    use FuncTrait;

    /**
     * Display a listing of the call queues.
     *
     * @return Illuminate\View\View
     */

    
    public function __construct(){
        config(['menu.group' => 'menu-callcenter']);  
    }  
    public function index( Request $request ) {

        $q         = $request->get( 'q' ) ?: '';
        $perPage   = $request->get( 'per_page' ) ?: 10;
        $filter    = $request->get( 'filter' ) ?: '';
        $sort      = $request->get( 'sort' ) ?: '';
        $callQueue = CallQueue::with( 'extension' )->where( 'organization_id', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $callQueue->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $callQueue->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $callQueue->orderBy( $sorta[0], $sorta[1] );
        } else {
            $callQueue->orderBy( 'created_at', 'DESC' );
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName   = 'call_queues.csv';
            $callQueues = $callQueue->get();

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            $columns = ['name', 'code', 'strategy', 'join_empty', 'leave_when_empty', 'member_timeout', 'queue_callback', 'queue_timeout', 'retry']; // specify columns if need

            $callback = function () use ( $callQueues, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $callQueues as $callQueue ) {

                    foreach ( $columns as $column ) {

                        if ( $column == 'code' ) {
                            $row[$column] = optional( $callQueue->extension )->code;
                        } 
                        else if(in_array($column, ['join_empty', 'leave_when_empty', 'queue_callback'])){
                            $row[$column] = $callQueue->{$column} ?  'Yes' : 'No';
                        }

                        else {
                            $row[$column] = $callQueue->{$column};
                        }

                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $callQueues = $callQueue->paginate( $perPage );

        $callQueues->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if ( $request->ajax() ) {
            return view( 'call_queues.table', compact( 'callQueues' ) );
        }

        return view( 'call_queues.index', compact( 'callQueues' ) );

    }

    public function destinations( $function ) {

        if ( request()->ajax() ) {
            return $this->dist_by_function( $function );
        }

        die();
    }

    /**
     * Show the form for creating a new call queue.
     *
     * @return Illuminate\View\View
     */

    public function create( Request $request ) {
        $voice_files = VoiceFile::where( 'organization_id', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();

        $functions    = Func::getFuncList();
        $destinations = [];
        $callQueue    = [];
        $extensions = Extension::select(DB::raw('CONCAT(name, " (", code, ")") AS full_name'),'id')->where( 'organization_id', auth()->user()->organization_id )->where( 'extension_type', 1 )->pluck( 'full_name', 'id' );
      
        if ( $request->ajax() ) {
            return view( 'call_queues.form', compact( 'extensions', 'callQueue', 'voice_files', 'functions', 'destinations' ) )->with( ['action' => route( 'call_queues.call_queue.store' ), 'callQueue' => null, 'method' => 'POST'] );
        } else {
            return view( 'call_queues.create', compact( 'extensions', 'callQueue', 'voice_files', 'functions', 'destinations' ) );
        }

    }

    /**
     * Store a new call queue in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $orid = auth()->user()->organization_id;
        $this->getData( $request );

        $queue_data = $request->except( ['_token', 'extensions'] );



        $queue_data['organization_id'] = $orid;

        $function = Func::select( 'id' )->where( 'func', 'call_queue' )->first();

        $extension = [
            'name'            => 'call queue',
            'organization_id' => $orid,
            'function_id'     => $function->id,
            'extension_type'  => 4,
            'destination_id'  => 0,
            'code'            => $queue_data['code'],
            'status'          => 1,
        ];

        $extension = Extension::create( $extension );

        $function = Func::select( 'id' )->where( 'func', $queue_data['function_id'] )->first();

        $queue_data['function_id']  = $function->id;
        $queue_data['extension_id'] = $extension->id;

        $joinextension = [
            'name'            => 'call queue join',
            'organization_id' => $orid,
            'function_id'     => 16,
            'extension_type'  => 4,
            'destination_id'  => 0,
            'code'            => $queue_data['login_code'],
            'status'          => 1,
        ];
        $joinextension = Extension::create( $joinextension );
        $queue_data['join_extension_id'] = $joinextension->id;


        if ( isset( $queue_data['agent_function_id'] ) ) {
            $agent_function                  = Func::select( 'id' )->where( 'func', $queue_data['agent_function_id'] )->first();
            $queue_data['agent_function_id'] = $agent_function->id;
        }

        $queue = CallQueue::create( $queue_data );

        $extension->update( ['destination_id' => $queue->id] );
        $joinextension->update( ['destination_id' => $queue->id] );
        $exts_data = $request->input( 'extensions' );

// return $exts_data;

        if ( count( $exts_data['extension_id'] ) && $exts_data['extension_id'][0] != null ) {
            $data = [];

            foreach ( $exts_data['extension_id'] as $key => $ext_id ) {

                if (  ! $ext_id ) {
                    continue;
                }

                $row['extension_id']    = $ext_id;
                $row['call_queue_id']   = $queue->id;
                $row['member_type']     = $exts_data['member_type'][$key];
                $row['priority']        = $exts_data['priority'][$key];
                $row['allow_diversion'] = isset( $exts_data['allow_diversion'][$key] ) ? 1 : 0;

                $data[] = $row;
            }

            if ( count( $data ) > 0 ) {
                CallQueueExtension::insert( $data );
            }

        }

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'call_queues.call_queue.index' )
            ->with( 'success_message', __( 'Call Queue was successfully added.' ) );

    }

    /**
     * Show the form for editing the specified call queue.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {
    
        if(! CallQueue::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
        return back();

        $callQueue = CallQueue::with( ['extension', 'joinExtension','queueExtensions', 'func'] )->findOrFail( $id );

        $voice_files = VoiceFile::where( 'organization_id', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();
        //dd($callQueue);
        $callQueue->code = $callQueue->extension->code;
        $callQueue->login_code = optional($callQueue->joinExtension)->code;
        $destinations    = $this->dist_by_function( $callQueue->func->func, 0, true );
        $functions       = Func::getFuncList();
        $extensions = Extension::select(DB::raw('CONCAT(name, " (", code, ")") AS full_name'),'id')->where( 'organization_id', auth()->user()->organization_id )->where( 'extension_type', 1 )->pluck( 'full_name', 'id' );
      
        if ( $request->ajax() ) {
            return view( 'call_queues.form', compact( 'extensions', 'callQueue', 'destinations', 'voice_files', 'functions' ) )->with( ['action' => route( 'call_queues.call_queue.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'call_queues.edit', compact( 'extensions', 'callQueue', 'destinations', 'voice_files', 'functions' ) );
        }

    }

    /**
     * Update the specified call queue in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        if(! CallQueue::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
        return back();

        $callQueue = callQueue::findOrFail( $id );
        $this->getData( $request, $callQueue->extension_id,$callQueue->join_extension_id );

        $data = $request->except( ['_token', '_method', 'extensions'] );

        $extension = Extension::findOrFail( $callQueue->extension_id );

        $extension->update( ['code' => $data['code']] );

        $join_extension = Extension::find( $callQueue->join_extension_id  );
        //dd($join_extension);
        if($join_extension) $join_extension->update( ['code' => $data['login_code']] );
        else{
            $joinextension = [
                'name'            => 'call queue join',
                'organization_id' => auth()->user()->organization_id,
                'function_id'     => 16,
                'extension_type'  => 4,
                'destination_id'  => $id,
                'code'            => $data['login_code'],
                'status'          => 1,
            ];
            $joinextension = Extension::create( $joinextension );
            $data['join_extension_id'] = $extension->id;
        }



        $function = Func::select( 'id' )->where( 'func', $data['function_id'] )->first();

        $data['function_id'] = $function->id;

        if ( isset( $data['agent_function_id'] ) ) {
            $agent_function            = Func::select( 'id' )->where( 'func', $data['agent_function_id'] )->first();
            $data['agent_function_id'] = $agent_function->id;
        }

        $callQueue->update( $data );

        CallQueueExtension::where( 'call_queue_id', $callQueue->id )->delete();

        $exts_data = $request->input( 'extensions' );

        if ( count( $exts_data['extension_id'] ) > 0 && $exts_data['extension_id'][0] != null ) {
            $data = [];

            foreach ( $exts_data['extension_id'] as $key => $ext_id ) {

                if (  ! $ext_id ) {
                    continue;
                }

                $row['extension_id']    = $ext_id;
                $row['call_queue_id']   = $callQueue->id;
                $row['member_type']     = $exts_data['member_type'][$key];
                $row['priority']        = $exts_data['priority'][$key] ?: 0;
                $row['allow_diversion'] = isset( $exts_data['allow_diversion'][$key] ) ? 1 : 0;

                $data[] = $row;
            }

            if ( count( $data ) ) {
                CallQueueExtension::insert( $data );
            }

        }

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'call_queues.call_queue.index' )
            ->with( 'success_message', __( 'Call Queue was successfully updated.' ) );

    }

    /**
     * Remove the specified call queue from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {
            if(! CallQueue::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $callQueue = CallQueue::findOrFail( $id );
            Extension::findOrFail( $callQueue->extension_id )->delete();
            Extension::findOrFail( $callQueue->join_extension_id )->delete();
            CallQueueExtension::where( 'call_queue_id', $callQueue->id )->delete();
            $callQueue->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'call_queues.call_queue.index' )
                    ->with( 'success_message', __( 'Call Queue was successfully deleted.' ) );
            }

        } catch ( Exception $exception ) {

            if ( $request->ajax() ) {
                return response()->json( ['success' => false] );
            } else {
                return back()->withInput()
                    ->withErrors( ['unexpected_error' => __( 'Unexpected error occurred while trying to process your request.' )] );
            }

        }

    }

    /**
     * update the specified call queue for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {
            if(! CallQueue::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $callQueue = CallQueue::findOrFail( $id );

            $callQueue->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified call queue for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        /* try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                CallQueue::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new CallQueue )->getTable(), $field ) ) {
                        CallQueue::whereIn( 'id', $ids )->update( [$field => $val] );
                    }

                }

            }

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }
 */
    }

    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getData( Request $request, $id = 0,$join_id = 0 ) {

        $code_unique_rule = Rule::unique( 'extensions' )->where( function ( $query ) use ( $request ) {
            return $query->where( 'code', $request->code )->where( 'organization_id', auth()->user()->organization_id );
        } );

        if ( $id > 0 ) {
            $code_unique_rule->ignore( $id );
        }

        $join_code_unique_rule = Rule::unique( 'extensions','code' )->where( function ( $query ) use ( $request ) {
            return $query->where( 'code', $request->login_code )->where( 'organization_id', auth()->user()->organization_id );
        } );

        if ( $join_id > 0 ) {
            $join_code_unique_rule->ignore( $join_id );
        }

        $rules = [
            'code'                      => ['required', 'numeric', 'min:0', 'max:2147483647', $code_unique_rule],
            'agent_announcemnet'        => 'nullable|numeric|min:1|max:2147483647',
            'cid_name_prefix'           => 'nullable|string|min:1|max:191',
            'name'                      => 'required|string|min:3|max:191',
            // 'description'        => 'required|string|min:1|max:191',
            'function_id'               => 'required|string|min:1|max:191',
            'destination_id'            => 'required|integer|min:1|max:191',
            'agent_function_id'         => 'nullable|string|min:1|max:191',
            'agent_destination_id'      => 'nullable|integer|min:1|max:191',
            'join_announcement'         => 'nullable|numeric|min:1|max:2147483647',
            'join_empty'                => 'nullable|string|min:1',
            'leave_when_empty'          => 'nullable|string|min:1',
            'member_timeout'            => 'required|numeric|min:1|max:2147483647',
            'music_on_hold'             => 'nullable|numeric|min:1|max:2147483647',
            'queue_callback'            => 'nullable|string|min:1',
            'queue_timeout'             => 'required|numeric|min:1|max:2147483647',
            'record'                    => 'nullable|string|min:1',
            'retry'                     => 'required|numeric|min:1|max:2147483647',
            'ring_busy_agent'           => 'nullable|numeric|string|min:1',
            'service_level'             => 'nullable|string|min:1|max:191',
            'strategy'                  => 'required|string|min:1',
            'timeout_priority'          => 'nullable|string|min:1|max:191',
            'wrap_up_time'              => 'nullable|numeric|min:0|max:2147483647',

            'allow_diversion.*'         => 'nullable|string|min:1',
            // 'call_queue_id'      => 'nullable',
            'extensions.extension_id.*' => 'nullable',
            'extensions.member_type.*'  => 'nullable|numeric|min:0|max:1',
            'extensions.priority.*'     => 'nullable|numeric|min:0|max:2147483647',
            'login_code'                => ['nullable',$join_code_unique_rule]
        ];

        $data = $request->validate( $rules );

        return $data;
    }

}
