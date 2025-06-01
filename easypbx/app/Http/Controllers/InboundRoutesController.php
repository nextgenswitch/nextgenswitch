<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\FuncTrait;
use App\Models\Func;
use App\Models\InboundRoute;
use Exception;
use Illuminate\Http\Request;
use Schema;

class InboundRoutesController extends Controller {

    use FuncTrait;
    /**
     * Display a listing of the inbound routes.
     *
     * @return Illuminate\View\View
     */
    public function __construct(){
        config(['menu.group' => 'menu-external']);  
    } 
    public function index( Request $request ) {

        $q            = $request->get( 'q' ) ?: '';
        $perPage      = $request->get( 'per_page' ) ?: 10;
        $filter       = $request->get( 'filter' ) ?: '';
        $sort         = $request->get( 'sort' ) ?: '';
        $inboundRoute = InboundRoute::with( 'func' )->where( 'organization_id', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $inboundRoute->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $inboundRoute->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $inboundRoute->orderBy( $sorta[0], $sorta[1] );
        } else {
            $inboundRoute->orderBy( 'created_at', 'DESC' );
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName      = 'inboundRoutes.csv';
            $inboundRoutes = $inboundRoute->get();

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            $columns = ['name', 'did_pattern', 'cid_pattern']; // specify columns if need

            $callback = function () use ( $inboundRoutes, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $inboundRoutes as $inboundRoute ) {

                    foreach ( $columns as $column ) {
                        $row[$column] = $inboundRoute->{$column};
                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $inboundRoutes = $inboundRoute->paginate( $perPage );

        $inboundRoutes->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if ( $request->ajax() ) {
            return view( 'inbound_routes.table', compact( 'inboundRoutes' ) );
        }

        return view( 'inbound_routes.index', compact( 'inboundRoutes' ) );

    }

    public function destinations( $function ) {

        if ( request()->ajax() ) {
            return $this->dist_by_function( $function );
        }

        die();

    }

    /**
     * Show the form for creating a new inbound route.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request ) {

        $functions    = Func::getFuncList();
        $destinations = [];

        if ( $request->ajax() ) {
            return view( 'inbound_routes.form', compact( 'functions', 'destinations' ) )->with( ['action' => route( 'inbound_routes.inbound_route.store' ), 'inboundRoute' => null, 'method' => 'POST'] );
        } else {
            return view( 'inbound_routes.create', compact( 'functions', 'destinations' ) );
        }

    }

    /**
     * Store a new inbound route in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $data = $this->getData( $request );

        $data['organization_id'] = auth()->user()->organization_id;

        $func = Func::select( 'id' )->where( 'func', $data['function_id'] )->first();

        $data['function_id'] = $func->id;

        InboundRoute::create( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'inbound_routes.inbound_route.index' )
            ->with( 'success_message', __( 'Inbound Route was successfully added.' ) );

    }

    /**
     * Show the form for editing the specified inbound route.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {
        
        if(! InboundRoute::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $inboundRoute = InboundRoute::findOrFail( $id );
        $functions    = Func::getFuncList();

        $destinations = $this->dist_by_function( $inboundRoute->func->func, 0, true );
        

        if ( $request->ajax() ) {
            return view( 'inbound_routes.form', compact( 'inboundRoute', 'destinations', 'functions' ) )->with( ['action' => route( 'inbound_routes.inbound_route.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'inbound_routes.edit', compact( 'inboundRoute', 'destinations', 'functions' ) );
        }

    }

    /**
     * Update the specified inbound route in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        $data                = $this->getData( $request );
        $func                = Func::select( 'id' )->where( 'func', $data['function_id'] )->first();
        $data['function_id'] = $func->id;

        if(! InboundRoute::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $inboundRoute = InboundRoute::findOrFail( $id );
        $inboundRoute->update( $data );
        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'inbound_routes.inbound_route.index' )
            ->with( 'success_message', __( 'Inbound Route was successfully updated.' ) );

    }

    /**
     * Remove the specified inbound route from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {
            if(! InboundRoute::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $inboundRoute = InboundRoute::findOrFail( $id );
            $inboundRoute->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'inbound_routes.inbound_route.index' )
                    ->with( 'success_message', __( 'Inbound Route was successfully deleted.' ) );
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
     * update the specified inbound route for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {
            if(! InboundRoute::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();
        
            $inboundRoute = InboundRoute::findOrFail( $id );

            $inboundRoute->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified inbound route for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );
            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                InboundRoute::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new InboundRoute )->getTable(), $field ) ) {
                        InboundRoute::whereIn( 'id', $ids )->update( [$field => $val] );
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
            'name'           => 'required|string|min:3|max:255',
            'did_pattern'    => 'required|string|min:1|max:255',
            'cid_pattern'    => 'nullable|string|min:1|max:255',
            'function_id'    => 'required',
            'destination_id' => 'required',
        ];

        $data = $request->validate( $rules );

        return $data;
    }

}
