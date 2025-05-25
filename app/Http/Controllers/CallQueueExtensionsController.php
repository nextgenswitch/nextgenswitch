<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CallQueue;
use App\Models\CallQueueExtension;
use App\Models\Extension;
use Exception;
use Illuminate\Http\Request;
use Schema;

class CallQueueExtensionsController extends Controller {

    /**
     * Display a listing of the call queue extensions.
     *
     * @return Illuminate\View\View
     */
    public function index( Request $request, $queue_id ) {

        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';

        $call_queue  = CallQueue::find( $queue_id );
        $call_queues = CallQueue::where( 'organization_id', auth()->user()->organization_id )->pluck( 'name', 'id' )->toArray();

        $callQueueExtension = CallQueueExtension::with( 'extension' )->where( 'call_queue_id', $queue_id );

        if (  ! empty( $q ) ) {
            $callQueueExtension->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $callQueueExtension->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $callQueueExtension->orderBy( $sorta[0], $sorta[1] );
        } else {
            $callQueueExtension->orderBy( 'created_at', 'DESC' );
        }

        $callQueueExtensions = $callQueueExtension->paginate( $perPage );

        $callQueueExtensions->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'callQueueExtensions.csv';

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            //$column = ['name','email','password']; // specify columns if need
            $columns = Schema::getColumnListing(  ( new CallQueueExtension )->getTable() );

            $callback = function () use ( $callQueueExtensions, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $callQueueExtensions as $callQueueExtension ) {

                    foreach ( $columns as $column ) {
                        $row[$column] = $callQueueExtension->{$column};
                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        if ( $request->ajax() ) {
            return view( 'call_queue_extensions.table', compact( 'callQueueExtensions', 'call_queue', 'call_queues' ) );
        }

        return view( 'call_queue_extensions.index', compact( 'callQueueExtensions', 'call_queue', 'call_queues' ) );

    }

    /**
     * Show the form for creating a new call queue extension.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request, $queue_id ) {

        $callQueues = CallQueue::where( 'organization_id', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();

        $extensions = Extension::where( 'extension_type', 1 )->where( 'organization_id', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();

        $call_queue = CallQueue::find( $queue_id );

        if ( $request->ajax() ) {
            return view( 'call_queue_extensions.form', compact( 'callQueues', 'extensions', 'call_queue' ) )->with( ['action' => route( 'call_queue_extensions.call_queue_extension.store' ), 'callQueueExtension' => null, 'method' => 'POST'] );
        } else {
            return view( 'call_queue_extensions.create', compact( 'callQueues', 'extensions', 'call_queue' ) );
        }

    }

    /**
     * Store a new call queue extension in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $data = $this->getData( $request );

        CallQueueExtension::create( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'call_queue_extensions.call_queue_extension.index' )
            ->with( 'success_message', __( 'Call Queue Extension was successfully added.' ) );

    }

    /**
     * Show the form for editing the specified call queue extension.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {

        if(! CallQueueExtension::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
                return back();
            
        $callQueueExtension = CallQueueExtension::findOrFail( $id );

        $callQueues = CallQueue::where( 'organization_id', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();

        $extensions = Extension::where( 'extension_type', 1 )->where( 'organization_id', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();

        $call_queue = CallQueue::find($callQueueExtension->call_queue_id);

        

        if ( $request->ajax() ) {
            return view( 'call_queue_extensions.form', compact( 'callQueueExtension', 'callQueues', 'extensions', 'call_queue' ) )->with( ['action' => route( 'call_queue_extensions.call_queue_extension.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'call_queue_extensions.edit', compact( 'callQueueExtension', 'callQueues', 'extensions', 'call_queue' ) );
        }
    }

    /**
     * Update the specified call queue extension in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        $data = $this->getData( $request );

        
        if(! CallQueueExtension::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
        return back();
    
        $callQueueExtension = CallQueueExtension::findOrFail( $id );
        $callQueueExtension->update( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'call_queue_extensions.call_queue_extension.index', $callQueueExtension->call_queue_id )
            ->with( 'success_message', __( 'Call Queue Extension was successfully updated.' ) );

    }

    /**
     * Remove the specified call queue extension from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {
            if(! CallQueueExtension::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $callQueueExtension = CallQueueExtension::findOrFail( $id );
            $callQueueExtension->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'call_queue_extensions.call_queue_extension.index' )
                    ->with( 'success_message', __( 'Call Queue Extension was successfully deleted.' ) );
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
     * update the specified call queue extension for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {
            if(! CallQueueExtension::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();
        
            $callQueueExtension = CallQueueExtension::findOrFail( $id );

            $callQueueExtension->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified call queue extension for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                CallQueueExtension::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new CallQueueExtension )->getTable(), $field ) ) {
                        CallQueueExtension::whereIn( 'id', $ids )->update( [$field => $val] );
                    }

                }

            }

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getData( Request $request ) {
        $rules = [
            'allow_diversion' => 'nullable|string|min:1',
            'call_queue_id'   => 'required',
            'extension_id'    => 'required',
            'member_type'     => 'required|numeric|min:-2147483648|max:2147483647',
            'priority'        => 'required|numeric|min:-2147483648|max:2147483647',
        ];

        $data = $request->validate( $rules );

        return $data;
    }

}
