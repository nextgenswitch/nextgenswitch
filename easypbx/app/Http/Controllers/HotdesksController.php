<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Hotdesk;
use App\Models\SipUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Schema;

class HotdesksController extends Controller {

    /**
     * Display a listing of the hotdesks.
     *
     * @return Illuminate\View\View
     */
    public function __construct(){
        config(['menu.group' => 'menu-extensions']);  
    } 

    public function index( Request $request ) {

        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';
        $hotdesk = Hotdesk::with( 'sipuser' )->where( 'organization_id', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $hotdesk->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $hotdesk->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $hotdesk->orderBy( $sorta[0], $sorta[1] );
        } else {
            $hotdesk->orderBy( 'created_at', 'DESC' );
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'hotdesks.csv';
            $hotdesks = $hotdesk->get();

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            $columns = ['name', 'username', 'password']; // specify columns if need

            $callback = function () use ( $hotdesks, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $hotdesks as $hotdesk ) {

                    foreach ( $columns as $column ) {

                        if ( $column == 'username' ) {
                            $row[$column] = $hotdesk->sipUser->username;
                        } elseif ( $column == 'password' ) {
                            $row[$column] = $hotdesk->sipUser->password;
                        } else {
                            $row[$column] = $hotdesk->{$column};
                        }

                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $hotdesks = $hotdesk->paginate( $perPage );

        $hotdesks->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if ( $request->ajax() ) {
            return view( 'hotdesks.table', compact( 'hotdesks' ) );
        }

        return view( 'hotdesks.index', compact( 'hotdesks' ) );

    }

    /**
     * Show the form for creating a new hotdesk.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request ) {
        $sipUsers = SipUser::pluck( 'id', 'id' )->all();

        if ( $request->ajax() ) {
            return view( 'hotdesks.form', compact( 'sipUsers' ) )->with( ['action' => route( 'hotdesks.hotdesk.store' ), 'hotdesk' => null, 'method' => 'POST'] );
        } else {
            return view( 'hotdesks.create', compact( 'sipUsers' ) );
        }

    }

    /**
     * Store a new hotdesk in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {
        $data = $this->getData( $request );

        $orid                   = auth()->user()->organization_id;
        $sip                    = $request->only( ['username', 'password', 'transport'] );
        $sip['organization_id'] = $orid;
        $sip['peer']            = '0';
        $sip['call_limit']      = isset( $data['call_limit'] ) ? $data['call_limit'] : '1';
        $sip                    = SipUser::create( $sip );

        $hotdesk = [
            'name'            => $data['name'],
            'organization_id' => $orid,
            'sip_user_id'     => $sip->id,
        ];

        Hotdesk::create( $hotdesk );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'hotdesks.hotdesk.index' )
            ->with( 'success_message', __( 'Hotdesk was successfully added.' ) );

    }

    /**
     * Show the form for editing the specified hotdesk.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {
        
        if(! Hotdesk::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $hotdesk  = Hotdesk::findOrFail( $id );
        $sipUsers = SipUser::pluck( 'id', 'id' )->all();

        if ( $request->ajax() ) {
            return view( 'hotdesks.form', compact( 'hotdesk', 'sipUsers' ) )->with( ['action' => route( 'hotdesks.hotdesk.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'hotdesks.edit', compact( 'hotdesk', 'sipUsers' ) );
        }

    }

    /**
     * Update the specified hotdesk in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        if(! Hotdesk::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $hotdesk = Hotdesk::findOrFail( $id );
        $data    = $this->getData( $request, $hotdesk->sip_user_id );

        $hotdesk->update( ['name' => $data['name']] );

        $sip_data = $request->only( ['username', 'password', 'transport'] );
        $sip_data['call_limit']      = isset( $data['call_limit'] ) ? $data['call_limit'] : '1';
        SipUser::where( 'id', $hotdesk->sip_user_id )->update( $sip_data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'hotdesks.hotdesk.index' )
            ->with( 'success_message', __( 'Hotdesk was successfully updated.' ) );

    }

    /**
     * Remove the specified hotdesk from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {

        try {
            if(! Hotdesk::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $hotdesk = Hotdesk::findOrFail( $id );
            SipUser::find( $hotdesk->sip_user_id )->delete();
            $hotdesk->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'hotdesks.hotdesk.index' )
                    ->with( 'success_message', __( 'Hotdesk was successfully deleted.' ) );
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
     * update the specified hotdesk for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {
            if(! Hotdesk::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();
        
            $hotdesk = Hotdesk::findOrFail( $id );

            $hotdesk->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified hotdesk for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                Hotdesk::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new Hotdesk )->getTable(), $field ) ) {
                        Hotdesk::whereIn( 'id', $ids )->update( [$field => $val] );
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
            'name'     => 'required|string|min:1|max:255',
            'username' => ['required', 'string', 'min:3', 'max:100', $username_unique_rule],
            'password' => 'required|string|min:6|max:32',
            // 'transport' => 'required|numeric|min:0|max:2'
            'call_limit' => 'nullable|numeric|min:1'
        ];

        $data = $request->validate( $rules );

        return $data;
    }

}
