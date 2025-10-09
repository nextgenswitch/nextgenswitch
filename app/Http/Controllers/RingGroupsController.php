<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\FuncTrait;
use App\Models\Extension;
use App\Models\Func;
use App\Models\RingGroup;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Schema;

class RingGroupsController extends Controller {
    use FuncTrait;

    /**
     * Display a listing of the ring groups.
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
        $ringGroup = RingGroup::with( ['extensiongroup', 'extension'] )->where( 'organization_id', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $ringGroup->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $ringGroup->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $ringGroup->orderBy( $sorta[0], $sorta[1] );
        } else {
            $ringGroup->orderBy( 'created_at', 'DESC' );
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName   = 'ringGroups.csv';
            $ringGroups = $ringGroup->get();

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            $columns = ['code', 'ring_strategy', 'ring_time', 'answer_channel', 'skip_busy_extension', 'allow_diversions', 'ringback_tone']; // specify columns if need

            $callback = function () use ( $ringGroups, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $ringGroups as $ringGroup ) {

                    foreach ( $columns as $column ) {

                        if ( $column == 'code' ) {
                            $row[$column] = $ringGroup->extension->code;
                        } 
                        else if($column == 'ring_strategy'){
                            $row[$column] = $ringGroup->{$column} ? 'One by one': 'Ring all';
                        }
                        
                        else if(in_array($column, ['ring_time', 'answer_channel', 'skip_busy_extension', 'allow_diversions', 'ringback_tone'])){
                            $row[$column] = $ringGroup->{$column} ?  'Yes' : 'No';
                        }

                        else {
                            $row[$column] = $ringGroup->{$column};
                        }

                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $ringGroups = $ringGroup->paginate( $perPage );

        $ringGroups->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if ( $request->ajax() ) {
            return view( 'ring_groups.table', compact( 'ringGroups' ) );
        }

        return view( 'ring_groups.index', compact( 'ringGroups' ) );

    }

    public function destinations( $function ) {

        if ( request()->ajax() ) {
            return $this->dist_by_function( $function );
        }

        die();
    }

    /**
     * Show the form for creating a new ring group.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request ) {
        $extensions = Extension::select(DB::raw('CONCAT(name, " (", code, ")") AS full_name'),'id')->where( 'organization_id', auth()->user()->organization_id )->where( 'extension_type', 1 )->pluck( 'full_name', 'id' );
       
        $functions    = Func::getFuncList();
        $destinations = [];

        if ( $request->ajax() ) {
            return view( 'ring_groups.form', compact( 'extensions', 'functions', 'destinations' ) )->with( ['action' => route( 'ring_groups.ring_group.store' ), 'ringGroup' => null, 'method' => 'POST'] );
        } else {
            return view( 'ring_groups.create', compact( 'extensions', 'functions', 'destinations' ) );
        }

    }

    /**
     * Store a new ring group in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $orid                    = auth()->user()->organization_id;
        $data                    = $this->getData( $request );
        $data['organization_id'] = $orid;
        $data['extensions']      = implode( ',', $data['extensions'] );

        $function = Func::select( 'id' )->where( 'func', 'ring_group' )->first();

        $extension = [
            'name'            => 'Ring Group',
            'organization_id' => $orid,
            'function_id'     => $function->id,
            'extension_type'  => 2,
            'destination_id'  => 0,
            'code'            => $data['code'],
            'status'          => 1,
        ];

        $extension = Extension::create( $extension );

        $function = Func::select( 'id' )->where( 'func', $data['function_id'] )->first();

        $data['function_id']  = $function->id;
        $data['extension_id'] = $extension->id;

        
        $data['answer_channel'] = isset($data['answer_channel']) ? $data['answer_channel'] : 0;
        $data['skip_busy_extension'] = isset($data['skip_busy_extension']) ? $data['skip_busy_extension'] : 0;
        $data['allow_diversions'] = isset($data['allow_diversions']) ? $data['allow_diversions'] : 0;
        $data['ringback_tone'] = isset($data['ringback_tone']) ? $data['ringback_tone'] : 0;


        $ringGroup = RingGroup::create( $data );

        $extension->update( ['destination_id' => $ringGroup->id] );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'ring_groups.ring_group.index' )
            ->with( 'success_message', __( 'Ring Group was successfully added.' ) );

    }

    /**
     * Show the form for editing the specified ring group.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {
        
        if(! RingGroup::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $ringGroup               = RingGroup::with( ['extension', 'func'] )->findOrFail( $id );
        $ringGroup['extensions'] = explode( ',', $ringGroup->extensions );

        $extensions = Extension::select(DB::raw('CONCAT(name, " (", code, ")") AS full_name'),'id')->where( 'organization_id', auth()->user()->organization_id )->where( 'extension_type', 1 )->pluck( 'full_name', 'id' );
        $ringGroup->code = $ringGroup->extension->code;

        $destinations = $this->dist_by_function( $ringGroup->func->func, 0, true );
        $functions    = Func::getFuncList();

        if ( $request->ajax() ) {
            return view( 'ring_groups.form', compact( 'ringGroup', 'extensions', 'destinations', 'functions' ) )->with( ['action' => route( 'ring_groups.ring_group.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'ring_groups.edit', compact( 'ringGroup', 'extensions', 'destinations', 'functions' ) );
        }

    }

    /**
     * Update the specified ring group in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {
        if(! RingGroup::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $ringGroup = RingGroup::findOrFail( $id );

        $data = $this->getData( $request, $ringGroup->extension_id );

        $data['extensions'] = implode( ',', $data['extensions'] );

        $extension = Extension::findOrFail( $ringGroup->extension_id );

        $extension->update( ['code' => $data['code']] );

        $function = Func::select( 'id' )->where( 'func', $data['function_id'] )->first();

        $data['function_id'] = $function->id;

        $data['answer_channel'] = isset($data['answer_channel']) ? $data['answer_channel'] : 0;
        $data['skip_busy_extension'] = isset($data['skip_busy_extension']) ? $data['skip_busy_extension'] : 0;
        $data['allow_diversions'] = isset($data['allow_diversions']) ? $data['allow_diversions'] : 0;
        $data['ringback_tone'] = isset($data['ringback_tone']) ? $data['ringback_tone'] : 0;


        $ringGroup->update( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'ring_groups.ring_group.index' )
            ->with( 'success_message', __( 'Ring Group was successfully updated.' ) );

    }

    /**
     * Remove the specified ring group from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {
           
            if(! RingGroup::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $ringGroup = RingGroup::findOrFail( $id );
            Extension::findOrFail( $ringGroup->extension_id )->delete();
            $ringGroup->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'ring_groups.ring_group.index' )
                    ->with( 'success_message', __( 'Ring Group was successfully deleted.' ) );
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
     * update the specified ring group for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {
        
            if(! RingGroup::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $ringGroup = RingGroup::findOrFail( $id );

            $ringGroup->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified ring group for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                $extension_ids = RingGroup::whereIn( 'id', $ids )->pluck( 'extension_id' )->toArray();
                Extension::whereIn( 'id', $extension_ids )->delete();
                RingGroup::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new RingGroup )->getTable(), $field ) ) {
                        RingGroup::whereIn( 'id', $ids )->update( [$field => $val] );
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
    protected function getData( Request $request, $id = 0 ) {
        $code_unique_rule = Rule::unique( 'extensions' )->where( function ( $query ) use ( $request ) {
            return $query->where( 'code', $request->code )->where( 'organization_id', auth()->user()->organization_id );
        } );

        if ( $id > 0 ) {
            $code_unique_rule->ignore( $id );
        }

        $rules = [
            'code'                => ['required', 'numeric', 'min:0', 'max:2147483647', $code_unique_rule],
            'description'         => 'required|string|min:1|max:255',
            'ring_strategy'       => 'required|string|min:1',
            'function_id'         => 'required|string|min:1',
            'destination_id'      => 'required|string|min:1',
            'ring_time'           => 'required|numeric|min:0|max:300',
            'answer_channel'      => 'nullable|string|min:1',
            'skip_busy_extension' => 'nullable|string|min:1',
            'allow_diversions'    => 'nullable|string|min:1',
            'ringback_tone'       => 'nullable|string|min:1',
            'extensions'          => 'required',
        ];

        $data = $request->validate( $rules );

        return $data;
    }

}
