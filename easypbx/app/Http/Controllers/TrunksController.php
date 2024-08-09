<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\FunctionCall;
use App\Http\Controllers\Controller;
use App\Models\SipUser;
use App\Models\Trunk;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Schema;

class TrunksController extends Controller {

    /**
     * Display a listing of the trunks.
     *
     * @return Illuminate\View\View
     */

    public function __construct(){
        config(['menu.group' => 'menu-external']);  
    } 
    public function index( Request $request ) {

        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';

        $trunk = Trunk::with( 'sipuser' )->where( 'organization_id', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $trunk->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $trunk->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $trunk->orderBy( $sorta[0], $sorta[1] );
        } else {
            $trunk->orderBy( 'created_at', 'DESC' );
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'trunks.csv';

            $trunks = $trunk->get();

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            $columns = ['name', 'email', 'password', 'peer']; // specify columns if need

            $callback = function () use ( $trunks, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $trunks as $trunk ) {

                    foreach ( $columns as $column ) {

                        if ( $column == 'username' || $column == 'password' ) {
                            $row[$column] = optional( $trunk->sipUser )->{$column};
                        } elseif ( $column == 'peer' ) {
                            $row[$column] = optional( $trunk->sipUser )->{$column} ? 'Yes' : 'No';
                        } else {
                            $row[$column] = $trunk->{$column};
                        }

                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );

        }

        $trunks = $trunk->paginate( $perPage );

        $trunks->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if ( $request->ajax() ) {
            return view( 'trunks.table', compact( 'trunks' ) );
        }

        return view( 'trunks.index', compact( 'trunks' ) );

    }

    /**
     * Show the form for creating a new trunk.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request ) {

        if ( $request->ajax() ) {
            return view( 'trunks.form' )->with( ['action' => route( 'trunks.trunk.store' ), 'trunk' => null, 'method' => 'POST'] );
        } else {
            return view( 'trunks.create' );
        }

    }

    /**
     * Store a new trunk in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {
        $data = $this->getData( $request );

        $orid = auth()->user()->organization_id;

        if (  ! isset( $data['peer'] ) ) {
            $sip         = $request->only( ['username', 'password'] );
            $sip['peer'] = 0;
        } else {
            $sip         = $request->only( ['username', 'password', 'host', 'port', 'transport'] );
            $sip['peer'] = 1;
        }

        $sip['record']          = (isset( $data['port'] ) && !empty($data['port'] )) ? 1 : 0;
        $sip['port']          = isset( $data['port'] ) ? $data['port']  : 5060;
        $sip['organization_id'] = $orid;
        $sip['call_limit']      = isset( $data['call_limit'] ) ? $data['call_limit'] : '0';
        $sip['user_type'] = 2;   
        $sip                    = SipUser::create( $sip );

        $data = [
            'name'            => $data['name'],
            'organization_id' => $orid,
            'sip_user_id'     => $sip->id,
        ];

        $trunk = Trunk::create( $data );
        FunctionCall::reg_channel( $trunk->sip_user_id );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'trunks.trunk.index' )
            ->with( 'success_message', __( 'Trunk was successfully added.' ) );

    }

    /**
     * Show the form for editing the specified trunk.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {
        
        if(! Trunk::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $trunk = Trunk::with( 'sipUser' )->findOrFail( $id );

        $sipUsers = SipUser::pluck( 'username', 'id' )->all();

        if ( $request->ajax() ) {
            return view( 'trunks.form', compact( 'trunk', 'sipUsers' ) )->with( ['action' => route( 'trunks.trunk.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'trunks.edit', compact( 'trunk', 'sipUsers' ) );
        }

    }

    /**
     * Update the specified trunk in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        if(! Trunk::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $trunk = Trunk::findOrFail( $id );
        $data  = $this->getData( $request, $trunk->sip_user_id );


        $trunk->update( ['name' => $data['name']] );

        if (  ! isset( $data['peer'] ) ) {
            $sip_data         = $request->only( ['username', 'password'] );
            $sip_data['peer'] = 0;
        } else {
            $sip_data         = $request->only( ['username', 'password', 'host', 'port', 'transport'] );
            $sip_data['peer'] = 1;
        }

        if( empty($sip_data['password']) || is_null($sip_data['password'])){
            unset($sip_data['password']);
        }

        $sip_data['port']          = (isset( $data['port'] ) && !empty($data['port'] )) ? $data['port']  : 5060;

        $sip_data['record'] = isset( $data['record'] ) ? 1 : 0;
        $sip_data['call_limit']      = isset( $data['call_limit'] ) ? $data['call_limit'] : '0';
        $sip_data['user_type'] = 2;   
        SipUser::where( 'id', $trunk->sip_user_id )->update( $sip_data );

        FunctionCall::reg_channel( $trunk->sip_user_id );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'trunks.trunk.index' )
            ->with( 'success_message', __( 'Trunk was successfully updated.' ) );

    }

    /**
     * Remove the specified trunk from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {
        
            if(! Trunk::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $trunk = Trunk::findOrFail( $id );
            SipUser::find( $trunk->sip_user_id )->delete();
            $trunk->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'trunks.trunk.index' )
                    ->with( 'success_message', __( 'Trunk was successfully deleted.' ) );
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
     * update the specified trunk for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {

            if(! Trunk::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();
        
            $trunk = Trunk::findOrFail( $id );

            $trunk->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified trunk for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                $sid_ids = Trunk::whereIn( 'id', $ids )->pluck( 'sip_user_id' )->toArray();
                SipUser::whereIn( 'id', $sid_ids )->delete();

                Trunk::whereIn( 'id', $ids )->delete();

            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new Trunk )->getTable(), $field ) ) {
                        Trunk::whereIn( 'id', $ids )->update( [$field => $val] );
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

        $username_unique_rule = Rule::unique( 'sip_users' )->where( function ( $query ) use ( $request ) {
            return $query->where( 'username', $request->username )->where( 'peer', 0 )->where( 'organization_id', auth()->user()->organization_id );
        } );

        if ( $id > 0 ) {
            $username_unique_rule->ignore( $id );
        }

        $rules = [
            'name'      => 'required|string|min:1|max:255',
            'username'  => ['required', 'string', 'min:3', 'max:100', $username_unique_rule],
            'password'  => 'required|string|min:6|max:32',
            'transport' => 'nullable|numeric|min:0|max:2',
            'host'      => 'nullable',
            'port'      => 'nullable|numeric',
            'peer'      => 'nullable',
            'record'    => 'nullable',
            'call_limit' => 'nullable|numeric|min:0'
        ];

        if ( $id > 0 ) {
            $rules['password']  = 'nullable|string|min:6|max:32';
        }

        $data = $request->validate( $rules );

        return $data;
    }

}
