<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Flow;
use App\Models\FlowAction;
use App\Models\VoiceFile;
use Exception;
use Illuminate\Http\Request;
use Schema;

class FlowsController extends Controller {

    /**
     * Display a listing of the flows.
     *
     * @return Illuminate\View\View
     */
    public function index( Request $request ) {

        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';
        $flow    = Flow::where( 'organization_id', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $flow->where( 'title', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $flow->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $flow->orderBy( $sorta[0], $sorta[1] );
        } else {
            $flow->orderBy( 'created_at', 'DESC' );
        }

        $flows = $flow->paginate( $perPage );

        $flows->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'flows.csv';

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            //$column = ['name','email','password']; // specify columns if need
            $columns = Schema::getColumnListing(  ( new Flow )->getTable() );

            $callback = function () use ( $flows, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $flows as $flow ) {

                    foreach ( $columns as $column ) {
                        $row[$column] = $flow->{$column};
                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        if ( $request->ajax() ) {
            return view( 'flows.table', compact( 'flows' ) );
        }

        return view( 'flows.index', compact( 'flows' ) );

    }

    /**
     * Show the form for creating a new flow.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request ) {
        $voices      = VoiceFile::where( 'organization_id', auth()->user()->organization_id )->pluck( 'name', 'id' );
        $flowActions = FlowAction::where( 'organization_id', auth()->user()->organization_id )->pluck( 'title', 'id' );

        if ( $request->ajax() ) {
            return view( 'flows.form', compact( 'voices', 'flowActions' ) )->with( ['action' => route( 'flows.flow.store' ), 'flow' => null, 'method' => 'POST'] );
        } else {
            return view( 'flows.create', compact( 'voices', 'flowActions' ) );
        }

    }

    /**
     * Store a new flow in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $data                    = $this->getData( $request );
        $data['organization_id'] = auth()->user()->organization_id;

        Flow::create( $data );
        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'flows.flow.index' )
            ->with( 'success_message', __( 'Flow was successfully added.' ) );

    }

    /**
     * Show the form for editing the specified flow.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {
        $flow = Flow::findOrFail( $id );

        $voices      = VoiceFile::where( 'organization_id', auth()->user()->organization_id )->pluck( 'name', 'id' );
        $flowActions = FlowAction::where( 'organization_id', auth()->user()->organization_id )->pluck( 'title', 'id' );

        if ( $request->ajax() ) {
            return view( 'flows.form', compact( 'flow', 'voices', 'flowActions' ) )->with( ['action' => route( 'flows.flow.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'flows.edit', compact( 'flow', 'voices', 'flowActions' ) );
        }

    }

    /**
     * Update the specified flow in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        $data = $this->getData( $request );

        $flow = Flow::findOrFail( $id );
        $flow->update( $data );
        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'flows.flow.index' )
            ->with( 'success_message', __( 'Flow was successfully updated.' ) );

    }

    /**
     * Remove the specified flow from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {
            $flow = Flow::findOrFail( $id );
            $flow->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'flows.flow.index' )
                    ->with( 'success_message', __( 'Flow was successfully deleted.' ) );
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
     * update the specified flow for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {
            $flow = Flow::findOrFail( $id );

            $flow->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified flow for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );
            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                Flow::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new Flow )->getTable(), $field ) ) {
                        Flow::whereIn( 'id', $ids )->update( [$field => $val] );
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
            'title'            => 'required|string',
            'matched_action'   => 'required|numeric',
            'unmatched_action' => 'required|numeric',
            'match_type'       => 'required|numeric',
            'matched_value'    => 'required|string|min:1|max:191',
            'voice_file'       => 'nullable|string|min:0|max:191',
        ];

        $data = $request->validate( $rules );

        return $data;
    }

}
