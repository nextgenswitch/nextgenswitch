<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sms;
use Exception;
use Illuminate\Http\Request;
use Schema;

class SmsController extends Controller {

    /**
     * Display a listing of the sms.
     *
     * @return Illuminate\View\View
     */
    public function index( Request $request ) {

        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';
        $sms     = Sms::where( 'organization_id', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $sms->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $sms->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $sms->orderBy( $sorta[0], $sorta[1] );
        } else {
            $sms->orderBy( 'created_at', 'DESC' );
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName   = 'sms.csv';
            $smsObjects = $sms->get();

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            //$column = ['name','email','password']; // specify columns if need
            $columns = Schema::getColumnListing(  ( new Sms )->getTable() );

            $callback = function () use ( $smsObjects, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $smsObjects as $sms ) {

                    foreach ( $columns as $column ) {
                        $row[$column] = $sms->{$column};
                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $smsObjects = $sms->paginate( $perPage );

        $smsObjects->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if ( $request->ajax() ) {
            return view( 'sms.table', compact( 'smsObjects' ) );
        }

        return view( 'sms.index', compact( 'smsObjects' ) );

    }

    /**
     * Show the form for creating a new sms.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request ) {

        if ( $request->ajax() ) {
            return view( 'sms.form' )->with( ['action' => route( 'sms.sms.store' ), 'sms' => null, 'method' => 'POST'] );
        } else {
            return view( 'sms.create' );
        }

    }

    /**
     * Store a new sms in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $data                    = $this->getData( $request );
        $data['organization_id'] = auth()->user()->organization_id;
        $data['sms_count']       = $this->sms_count( $data['content'] );

        Sms::create( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'sms.sms.index' )
            ->with( 'success_message', __( 'Sms was successfully added.' ) );

    }

    /**
     * Show the form for editing the specified sms.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {
        
        if(! Sms::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $sms = Sms::findOrFail( $id );

        if ( $request->ajax() ) {
            return view( 'sms.form', compact( 'sms' ) )->with( ['action' => route( 'sms.sms.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'sms.edit', compact( 'sms' ) );
        }

    }

    /**
     * Update the specified sms in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        $data = $this->getData( $request );
        $data['sms_count'] = $this->sms_count($data['content']);

        if(! Sms::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $sms = Sms::findOrFail( $id );
        $sms->update( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'sms.sms.index' )
            ->with( 'success_message', __( 'Sms was successfully updated.' ) );

    }

    /**
     * Remove the specified sms from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {
            if(! Sms::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $sms = Sms::findOrFail( $id );
            $sms->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'sms.sms.index' )
                    ->with( 'success_message', __( 'Sms was successfully deleted.' ) );
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
     * update the specified sms for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {
            if(! Sms::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();
        
            $sms = Sms::findOrFail( $id );

            $sms->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified sms for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                Sms::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new Sms )->getTable(), $field ) ) {
                        Sms::whereIn( 'id', $ids )->update( [$field => $val] );
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
            'content' => 'required',
            'title'   => 'required|string|min:3|max:191',
        ];

        $data = $request->validate( $rules );

        return $data;
    }

    public function sms_count( $sms ) {

        $smsLen   = strlen( $sms );
        $maxLimit = $this->isUnicode( $sms ) ? 1005 : 2095;
        $perSMS   = 160;

        if ( $this->isUnicode( $sms ) && $smsLen <= 70 ) {
            $perSMS = 70;
        } elseif ( $this->isUnicode( $sms ) && $smsLen > 70 ) {
            $perSMS = 67;
        } elseif ( $smsLen > 160 ) {
            $perSMS = 153;
        }

        return ceil( $smsLen / $perSMS );

    }

    public function isUnicode( $string ) {
        return strlen( $string ) != strlen( utf8_decode( $string ) ) ? true : false;
    }

}
