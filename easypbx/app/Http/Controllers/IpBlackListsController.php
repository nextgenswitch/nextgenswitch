<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\IpBlackList;
use Exception;
use Illuminate\Http\Request;
use Schema;

class IpBlackListsController extends Controller {

    /**
     * Show the form for creating a new ip black list.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request ) {

        if ( $request->ajax() ) {
            return view( 'firewall.ip.form' )->with( ['action' => route( 'ip_black_lists.ip_black_list.store' ), 'ipBlackList' => null, 'method' => 'POST'] );
        }

    }

    /**
     * Store a new ip black list in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $data                    = $this->getData( $request );
        $data['organization_id'] = auth()->user()->organization_id;

        IpBlackList::create( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

    }

    /**
     * Show the form for editing the specified ip black list.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {

        if (  ! IpBlackList::where( 'id', $id )->where( 'organization_id', auth()->user()->organization_id )->exists() ) {
            return back();
        }

        $ipBlackList = IpBlackList::findOrFail( $id );

        if ( $request->ajax() ) {
            return view( 'firewall.ip.form', compact( 'ipBlackList' ) )->with( ['action' => route( 'ip_black_lists.ip_black_list.update', $id ), 'method' => 'PUT'] );
        }

    }

    /**
     * Update the specified ip black list in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        $data = $this->getData( $request );

        if (  ! IpBlackList::where( 'id', $id )->where( 'organization_id', auth()->user()->organization_id )->exists() ) {
            return back();
        }

        $ipBlackList = IpBlackList::findOrFail( $id );
        $ipBlackList->update( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

    }

    /**
     * Remove the specified ip black list from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {

        if (  ! IpBlackList::where( 'id', $id )->where( 'organization_id', auth()->user()->organization_id )->exists() ) {
            return back();
        }

        $ipBlackList = IpBlackList::findOrFail( $id );
        $ipBlackList->delete();

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

    }

    /**
     * update the specified ip black list for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {

            if (  ! IpBlackList::where( 'id', $id )->where( 'organization_id', auth()->user()->organization_id )->exists() ) {
                return back();
            }

            $ipBlackList = IpBlackList::findOrFail( $id );

            $ipBlackList->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified ip black list for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                IpBlackList::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn( ( new IpBlackList )->getTable(), $field ) ) {
                        IpBlackList::whereIn( 'id', $ids )->update( [$field => $val] );
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
            'title'  => 'nullable|string|max:191',
            'ip'     => 'required|string|min:1|max:191',
            'subnet' => 'nullable|numeric|min:-2147483648|max:2147483647',
        ];

        $data = $request->validate( $rules );

        return $data;
    }

}
