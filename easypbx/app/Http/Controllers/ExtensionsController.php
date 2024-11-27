<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CallQueue;
use App\Models\Extension;
use App\Models\SipUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Schema;

class ExtensionsController extends Controller {

    /**
     * Display a listing of the extensions.
     *
     * @return Illuminate\View\View
     */

     
    public function __construct(){
        config(['menu.group' => 'menu-extensions']);  
    } 
    public function index( Request $request ) {

        $q         = $request->get( 'q' ) ?: '';
        $perPage   = $request->get( 'per_page' ) ?: 10;
        $filter    = $request->get( 'filter' ) ?: '';
        $sort      = $request->get( 'sort' ) ?: '';
        $extension = Extension::with( 'sipuser' )->where( 'organization_id', auth()->user()->organization_id )->where( 'extension_type', '1' );

        if (  ! empty( $q ) ) {
            $extension->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $extension->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $extension->orderBy( $sorta[0], $sorta[1] );
        } else {
            $extension->orderBy( 'created_at', 'DESC' );
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName   = 'extensions.csv';
            $extensions = $extension->get();

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            $columns  = ['name', 'username', 'password', 'code', 'active', 'forwarding_number','record','call_limit','allowed_ip'];
            $callback = function () use ( $extensions, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $extensions as $extension ) {

                    foreach ( $columns as $column ) {

                        if ( $column == 'username' ) {
                            $row[$column] = $extension->sipUser->username;
                        } elseif ( $column == 'password' ) {
                            $row[$column] = $extension->sipUser->password;
                        }elseif($column == 'allowed_ip') {
                            $row[$column] = $extension->sipUser->allow_ip;
                        }elseif($column == 'call_limit') {
                            $row[$column] = $extension->sipUser->call_limit;
                        }elseif($column == 'record') {
                            $row[$column] = $extension->sipUser->record;
                        }elseif($column == 'active') {
                            $row[$column] = $extension->status;
                        }else {
                            $row[$column] = $extension->{$column};
                        }

                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $extensions = $extension->paginate( $perPage );

        $extensions->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if ( $request->ajax() ) {
            return view( 'extensions.table', compact( 'extensions' ) );
        }

        return view( 'extensions.index', compact( 'extensions' ) );

    }

    public function import( Request $request ) {
        $rules = [
            'file' => 'required|mimes:csv,txt|max:40960',
        ];

        $request->validate( $rules );

        if ( $request->file() ) {

            $path = $request->file( 'file' )->getRealPath();
            $csv  = array_map( 'str_getcsv', file( $path ) );

            $headers = array_shift( $csv );
            $data    = [];
            $orid    = auth()->user()->organization_id;

            foreach ( $csv as $row ) {
                $assocArr = array_combine( $headers, $row );

                if (  ! Extension::where('organization_id',$orid)->where( 'code', $assocArr['code'] )->exists() &&
                        !SipUser::where('peer',0)->where("organization_id",$orid)->where("username",$assocArr['username'])->exists()
                ) { // dd($assocArr);
                    $sip = [
                        'organization_id' => $orid,
                        'username'        => $assocArr['username'],
                        'password'        => $assocArr['password'],
                        'allow_ip'        => $assocArr['allowed_ip'],
                        'call_limit'      => $assocArr['call_limit'],
                        'record'          => $assocArr['record'],       
                        'peer'            => '0',
                    ];
                  


                    $sip = SipUser::create( $sip );

                    $data[] = [
                        'name'              => $assocArr['name'],
                        'organization_id'   => $orid,
                        'destination_id'    => $sip->id,
                        'code'              => $assocArr['code'],
                        'forwarding_number' => $assocArr['forwarding_number'],
                        'status'            => $assocArr['active'],
                                         
                    ];
                }

            }

            if ( count( $data ) ) {
                Extension::insert( $data );

                return redirect()->route( 'extensions.extension.index' )
                    ->with( 'success_message', __( "Extensions imported successfully" ) );
            }

            return redirect()->route( 'extensions.extension.index' )
                ->with( 'error_message', __( "There are no new extensions available for import at the moment." ) );

        }

        return redirect()->route( 'extensions.extension.index' )
            ->with( 'error_message', __( "The extension could be imported. Please try again." ) );

    }

    /**
     * Show the form for creating a new extension.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request ) {

        /*
        $orid = auth()->user()->organization_id;
        $extension = Extension::where('organization_id', $orid)->latest()->first();

        $code = $extension ? $extension->code + 1 : 1000;

        while (Extension::where('code', $code)->where('organization_id', $orid)->exists()) {
        $code++;
        }

        $extension = new Extension(['code' => $code]);
        $extension->code = $code;

         */

        $code       = Extension::generateUniqueCode();
        $extension  = new Extension( ['code' => $code] );
        $callQueues = CallQueue::where( 'organization_id', auth()->user()->organization_id )->pluck( 'name', 'id' );

        if ( $request->ajax() ) {
            return view( 'extensions.form', compact( 'callQueues' ) )->with( ['action' => route( 'extensions.extension.store' ), 'extension' => $extension, 'method' => 'POST'] );
        } else {
            return view( 'extensions.create', compact( 'callQueues' ) )->with( ['extension' => $extension] );
        }

    }

    /**
     * Store a new extension in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $data = $this->getData( $request );
        $orid = auth()->user()->organization_id;

        $sip                    = $request->only( ['username', 'password', 'transport', 'allow_ip'] );
        $sip['organization_id'] = $orid;
        $sip['peer']            = '0';
        $sip['record']          = isset( $data['record'] ) ? $data['record'] : '0';
        $sip['status']          = isset( $data['status'] ) ? $data['status'] : '0';
        $sip['call_limit']      = isset( $data['call_limit'] ) ? $data['call_limit'] : '1';
        $sip                    = SipUser::create( $sip );

        $extension = [
            'name'              => $data['name'],
            'organization_id'   => $orid,
            'function_id'       => 1,
            'extension_type'    => 1,
            'destination_id'    => $sip->id,
            'code'              => $data['code'],
            'forwarding_number' => $data['forwarding_number'],
            'status'            => isset( $data['status'] ) ? $data['status'] : '0',
        ];

        Extension::create( $extension );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'extensions.extension.index' )
            ->with( 'success_message', __( 'Extension was successfully added.' ) );

    }

    /**
     * Show the form for editing the specified extension.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {
        
        if(! Extension::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $extension  = Extension::findOrFail( $id );
        $callQueues = CallQueue::where( 'organization_id', auth()->user()->organization_id )->pluck( 'name', 'id' );

        if ( $request->ajax() ) {
            return view( 'extensions.form', compact( 'extension', 'callQueues' ) )->with( ['action' => route( 'extensions.extension.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'extensions.edit', compact( 'extension', 'callQueues' ) );
        }

    }

    /**
     * Update the specified extension in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        $this->getData( $request, $id );

        if(! Extension::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $extension = Extension::findOrFail( $id );
        $ext_data  = $request->only( ['name', 'code', 'status', 'forwarding_number'] );

        $ext_data['status'] = isset( $ext_data['status'] ) ? $ext_data['status'] : '0';

        $extension->update( $ext_data );

        $sip_data               = $request->only( ['username', 'password', 'transport', 'record', 'call_limit', 'allow_ip'] );
        $sip_data['record']     = isset( $sip_data['record'] ) ? $sip_data['record'] : '0';
        $sip_data['status']     = isset( $ext_data['status'] ) ? $ext_data['status'] : '0';
        $sip_data['call_limit'] = isset( $sip_data['call_limit'] ) ? $sip_data['call_limit'] : '1';

        
        SipUser::where( 'id', $extension->destination_id )->update( $sip_data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'extensions.extension.index' )
            ->with( 'success_message', __( 'Extension was successfully updated.' ) );

    }




    /**
     * Remove the specified extension from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {
            if(! Extension::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $extension = Extension::findOrFail( $id );
            SipUser::find( $extension->destination_id )->delete();
            $extension->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'extensions.extension.index' )
                    ->with( 'success_message', __( 'Extension was successfully deleted.' ) );
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
     * update the specified extension for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {
            if(! Extension::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();
        
            $extension = Extension::findOrFail( $id );
            $data      = $request->all();
            $extension->update( $request->all() );

            if ( isset( $data['status'] ) ) {
                SipUser::where( 'id', $extension->destination_id )->update( ['status' => $data['status']] );
            }

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified extension for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                $sid_ids = Extension::whereIn( 'id', $ids )->pluck( 'destination_id' )->toArray();
                SipUser::whereIn( 'id', $sid_ids )->delete();
                Extension::whereIn( 'id', $ids )->delete();

            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new Extension )->getTable(), $field ) ) {
                        Extension::whereIn( 'id', $ids )->update( [$field => $val] );
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

        $extension_unique_rule = Rule::unique( 'extensions' )->where( function ( $query ) use ( $request ) {
            return $query->where( 'code', $request->code )->where( 'organization_id', auth()->user()->organization_id );
        } );

        $username_unique_rule = Rule::unique( 'sip_users' )->where( function ( $query ) use ( $request ) {
            return $query->where( 'username', $request->username )->where( 'peer', 1 )->where( 'organization_id', auth()->user()->organization_id );
        } );

        if ( $id > 0 ) {
            $extension_unique_rule->ignore( $id );
            $username_unique_rule->ignore( $request->sip_user_id );
        }

        $rules = [
            'name'              => 'required|string|min:1|max:255',
            'username'          => ['required', 'string', 'min:3', 'max:100', $username_unique_rule],
            'password'          => 'required|string|min:6|max:32',
            // 'transport' => 'required|numeric|min:0|max:2',
            'code'              => ['required', 'numeric', 'min:1000', 'max:2147483647', $extension_unique_rule],
            'status'            => 'nullable|min:1',
            'record'            => 'nullable|min:1',
            'forwarding_number' => 'nullable',
            'allow_ip' => 'nullable',
            'call_limit'        => 'nullable|numeric|min:1',
        ];

        return $request->validate( $rules );
    }

}
