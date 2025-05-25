<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\FuncTrait;
use App\Models\Func;
use App\Models\OutboundRoute;
use App\Models\PinList;
use App\Models\Trunk;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Schema;

class OutboundRoutesController extends Controller {
    use FuncTrait;
    /**
     * Display a listing of the outbound routes.
     *
     * @return Illuminate\View\View
     */
    public function __construct(){
        config(['menu.group' => 'menu-external']);  
    } 
    public function index( Request $request ) {
        $q             = $request->get( 'q' ) ?: '';
        $perPage       = $request->get( 'per_page' ) ?: 10;
        $filter        = $request->get( 'filter' ) ?: '';
        $sort          = $request->get( 'sort' ) ?: '';
        $outboundRoute = OutboundRoute::where( 'organization_id', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $outboundRoute->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $q ) ) {
            $outboundRoute->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $outboundRoute->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $outboundRoute->orderBy( $sorta[0], $sorta[1] );
        } else {
            $outboundRoute->orderBy( 'created_at', 'DESC' );
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {
            $fileName       = 'outboundRoutes.csv';
            $outboundRoutes = $outboundRoute->get();

            $headers = [
                'Content-type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];

            $columns = ['name', 'trunk', 'status', 'priority']; // specify columns if need

            $callback = function () use ( $outboundRoutes, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $outboundRoutes as $outboundRoute ) {

                    foreach ( $columns as $column ) {

                        if ( $column == 'trunk' ) {
                            $row[$column] = optional( $outboundRoute->trunk )->name;
                        } elseif ( $column == 'status' ) {
                            $row[$column] = $outboundRoute->is_active == 1 ? __( 'Yes' ) : __( 'No' );
                        } else {
                            $row[$column] = $outboundRoute->{$column};
                        }

                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $outboundRoutes = $outboundRoute->paginate( $perPage );

        $outboundRoutes->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if ( $request->ajax() ) {
            return view( 'outbound_routes.table', compact( 'outboundRoutes' ) );
        }

        return view( 'outbound_routes.index', compact( 'outboundRoutes' ) );
    }

    public function destinations( $function ) {

        if ( request()->ajax() ) {
            return $this->dist_by_function( $function );
        }

        die();
    }

    /**
     * Show the form for creating a new outbound route.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request, $api = 0 ) {
        $trunks = Trunk::where( 'organization_id', auth()->user()->organization_id )
            ->pluck( 'name', 'id' )
            ->all();

        $pinList = PinList::where( 'organization_id', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();

        $functions    = Func::getFuncList();
        $destinations = [];

        if ( $request->ajax() ) {
            return view( 'outbound_routes.form', compact( 'trunks' ) )->with( ['action' => route( 'outbound_routes.outbound_route.store' ), 'outboundRoute' => null, 'method' => 'POST'] );
        } else {
            return view( 'outbound_routes.create', compact( 'trunks', 'functions', 'destinations', 'pinList', 'api' ) );
        }

    }

    /**
     * Store a new outbound route in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {
        
        if ( $request->has('type')  == false ) {
            $data = $this->getData( $request );

            $func = Func::select( 'id' )->where( 'func', $data['function_id'] )->first();

            $data['function_id'] = $func->id;
        }
        else{

            $data = $this->getData( $request , 1);
        }

        $data['organization_id'] = auth()->user()->organization_id;
        $pdata = [];

        foreach ( $data['pattern']['pattern'] as $key => $pattern ) {

            if ( empty( $pattern ) ) {
                continue;
            }

            $pdata[] = [
                'prefix_append' => isset( $data['pattern']['prefix_append'][$key] ) ? $data['pattern']['prefix_append'][$key] : '',
                'prefix_remove' => isset( $data['pattern']['prefix_remove'][$key] ) ? $data['pattern']['prefix_remove'][$key] : '',
                'cid_pattern'   => isset( $data['pattern']['cid_pattern'][$key] ) ? $data['pattern']['cid_pattern'][$key] : '',
                'pattern'       => $pattern,

            ];
        }

        $data['pattern'] = json_encode( $pdata );
        OutboundRoute::create( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()
            ->route( 'outbound_routes.outbound_route.index' )
            ->with( 'success_message', __( 'Outbound Route was successfully added.' ) );
    }

    /**
     * Show the form for editing the specified outbound route.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {
        
        if(! OutboundRoute::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $outboundRoute = OutboundRoute::findOrFail( $id );

        $api = $outboundRoute->type;
        $destinations = array();
        $functions = array();
                
        $trunks = Trunk::where( 'organization_id', auth()->user()->organization_id )
            ->pluck( 'name', 'id' )
            ->all();

        $pinList = PinList::where( 'organization_id', auth()->user()->organization_id )->pluck( 'name', 'id' )->all();
        if($api == 0){
            $functions = Func::getFuncList();

            $destinations = $this->dist_by_function( $outboundRoute->func->func, 0, true );
        }
        

        if ( $request->ajax() ) {
            return view( 'outbound_routes.form', compact( 'outboundRoute', 'trunks' ) )->with( ['action' => route( 'outbound_routes.outbound_route.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'outbound_routes.edit', compact( 'outboundRoute', 'trunks', 'functions', 'destinations', 'pinList', 'api' ) );
        }

    }

    /**
     * Update the specified outbound route in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {
        

        

        if ( $request->has('type')  == false ) {
            $data = $this->getData( $request );

            $func = Func::select( 'id' )->where( 'func', $data['function_id'] )->first();

            $data['function_id'] = $func->id;
        }
        else{

            $data = $this->getData( $request , 1);
        }

        if(! OutboundRoute::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $outboundRoute = OutboundRoute::findOrFail( $id );

        $pdata = [];

        foreach ( $data['pattern']['pattern'] as $key => $pattern ) {

            if ( empty( $pattern ) ) {
                continue;
            }

            $pdata[] = [
                'prefix_append' => isset( $data['pattern']['prefix_append'][$key] ) ? $data['pattern']['prefix_append'][$key] : '',
                'prefix_remove' => isset( $data['pattern']['prefix_remove'][$key] ) ? $data['pattern']['prefix_remove'][$key] : '',
                'cid_pattern'   => isset( $data['pattern']['cid_pattern'][$key] ) ? $data['pattern']['cid_pattern'][$key] : '',
                'pattern'       => $pattern,

            ];
        }

        $data['pattern'] = json_encode( $pdata );

        $outboundRoute->update( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()
            ->route( 'outbound_routes.outbound_route.index' )
            ->with( 'success_message', __( 'Outbound Route was successfully updated.' ) );
    }

    /**
     * Remove the specified outbound route from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {
        
            if(! OutboundRoute::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $outboundRoute = OutboundRoute::findOrFail( $id );
            $outboundRoute->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()
                    ->route( 'outbound_routes.outbound_route.index' )
                    ->with( 'success_message', __( 'Outbound Route was successfully deleted.' ) );
            }

        } catch ( Exception $exception ) {

            if ( $request->ajax() ) {
                return response()->json( ['success' => false] );
            } else {
                return back()
                    ->withInput()
                    ->withErrors( ['unexpected_error' => __( 'Unexpected error occurred while trying to process your request.' )] );
            }

        }

    }

    /**
     * update the specified outbound route for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {
        try {
            if(! OutboundRoute::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();
        
            $outboundRoute = OutboundRoute::findOrFail( $id );

            $outboundRoute->update( $request->all() );

            return response()->json( ['success' => true] );
        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified outbound route for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {
        try {
            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                OutboundRoute::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new OutboundRoute() )->getTable(), $field ) ) {
                        OutboundRoute::whereIn( 'id', $ids )->update( [$field => $val] );
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
    protected function getData( Request $request, $api = 0 ) {
        $rules = [
            'priority'       => 'required|integer',
            'is_active'      => 'nullable|min:1',
            'record'         => 'nullable|min:1',
            'name'           => 'required|string|min:1|max:255',
            'pattern.*'      => 'required',
            'trunk_id'       => 'required',
            'function_id'    => 'required|string|min:1|max:255',
            'destination_id' => 'required|integer',
            'pin_list_id'    => 'nullable|integer',
        ];

        if ( $api > 0 ) {
            $rules = [
                'priority'  => 'required|integer',
                'is_active' => 'nullable|min:1',
                'record'    => 'nullable|min:1',
                'name'      => 'required|string|min:1|max:255',
                'pattern.*' => 'required',
                'trunk_id'  => 'required',
                'type'      => 'required'
            ];
        }

        $data = $request->validate( $rules );
        //dd($data);

        return $data;
    }

}
