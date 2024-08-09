<?php

namespace App\Http\Controllers;

use Schema;
use Exception;
use App\Mail\Mail;
use App\Models\Plan;
use App\Models\User;
use App\Models\SipUser;
use App\Models\Extension;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class OrganizationsController extends Controller {

    public function __construct() {

        if( !config('licence.multi_tenant')){

            redirect()->route('dashboard')->send();
        }

        config(['menu.group' => 'menu-multitenant']);
    }
    
    /**
     * Display a listing of the organizations.
     *
     * @return Illuminate\View\View
     */
    public function index( Request $request ) {
        

        $q            = $request->get( 'q' ) ?: '';
        $perPage      = $request->get( 'per_page' ) ?: 10;
        $filter       = $request->get( 'filter' ) ?: '';
        $sort         = $request->get( 'sort' ) ?: '';
        $organization = Organization::with( 'plan' );

        if (  ! empty( $q ) ) {
            $organization->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $organization->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $organization->orderBy( $sorta[0], $sorta[1] );
        } else {
            $organization->orderBy( 'created_at', 'DESC' );
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName      = 'organizations.csv';
            $organizations = $organization->get();

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            $columns = ['name', 'domain', 'contact_no', 'email', 'address'];

            $callback = function () use ( $organizations, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $organizations as $organization ) {

                    foreach ( $columns as $column ) {
                        $row[$column] = $organization->{$column};
                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $organizations = $organization->paginate( $perPage );

        $organizations->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        if ( $request->ajax() ) {
            return view( 'organizations.table', compact( 'organizations' ) );
        }

        return view( 'organizations.index', compact( 'organizations' ) );

    }

    /**
     * Show the form for creating a new organization.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request ) {
        $plans = Plan::pluck( 'name', 'id' )->all();

        if ( $request->ajax() ) {
            return view( 'organizations.form', compact( 'plans' ) )->with( ['action' => route( 'organizations.organization.store' ), 'organization' => null, 'method' => 'POST'] );
        } else {
            return view( 'organizations.create', compact( 'plans' ) );
        }

    }

    public function dns($ip, $subdomain){

        $data = [
            'content' => $ip,
            'name' => trim($subdomain) . '.nextgenswitch.com',
            'proxied' => false,
            'type' => 'A',
            'comment' =>  'Domain verification record',
            'ttl' => 1
        ];

        $url = 'https://api.cloudflare.com/client/v4/zones/61427525e2ad34b9f4e9a17321962faa/dns_records';
        $response = Http::withToken('KMiulacuiFLBsT0Q4Iu2DN-qNqHIsUMA72uixDii')->acceptJson()->post($url, $data);
        
        return $response;

    }

    /**
     * Store a new organization in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $data = $this->getData( $request );

        $organization = Organization::create( $data );

        $user = User::create( ['name' => $data['name'], 'email' => $data['email'], 'password' => Hash::make( $data['password'] ), 'organization_id' => $organization->id] );

        $mail = Mail::send($organization->email, __("Welcome to NextGenSwitch"), $data, 'welcome');

        if(is_array($mail) && $mail['status'] == false){
                session()->flash('error_message', implode(",", array_values($mail['errors'])));
        }

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'organizations.organization.index' )
            ->with( 'success_message', __( 'Organization was successfully added.' ) );

    }

    public function login($id){
        //$organization = Organization::findOrFail( $id );
        $user = User::where("organization_id",$id)->where('role','Admin')->first();
        Auth::login($user);
        return redirect('/');
    }

    /**
     * Show the form for editing the specified organization.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {
        $organization = Organization::findOrFail( $id );
        $plans        = Plan::pluck( 'name', 'id' )->all();

        if ( $request->ajax() ) {
            return view( 'organizations.form', compact( 'organization', 'plans' ) )->with( ['action' => route( 'organizations.organization.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'organizations.edit', compact( 'organization', 'plans' ) );
        }

    }

    /**
     * Update the specified organization in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        $data = $this->getData( $request );

        $organization = Organization::findOrFail( $id );
        $organization->update( $data );

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'organizations.organization.index' )
            ->with( 'success_message', __( 'Organization was successfully updated.' ) );

    }

    /**
     * Remove the specified organization from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        try {
            $organization = Organization::findOrFail( $id );
            if($organization->is_default == true) throw new Exception('Default tenant could not be deleted');
            $extension = Extension::where("organization_id",$id)->count();
            $sip_users = SipUser::where("organization_id",$id)->count();
            if($extension ||  $sip_users) throw new Exception('Could not delete as tenant has data');

            $organization->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'organizations.organization.index' )
                    ->with( 'success_message', __( 'Tenant was successfully deleted.' ) );
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
     * update the specified organization for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {
            $organization = Organization::findOrFail( $id );

            $organization->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified organization for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                Organization::whereIn( 'id', $ids )->where('is_default','!=',1)->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new Organization )->getTable(), $field ) ) {
                        Organization::whereIn( 'id', $ids )->update( [$field => $val] );
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
            'plan_id'    => 'nullable',
            'name'       => 'required|string|min:1|max:255',
            'domain'     => 'required|string|min:1|max:255',
            'contact_no' => 'required|string|min:1|max:255',
            'email'      => 'required|string|min:1|max:255',
            'address'    => 'required',
            'password'   => 'nullable|string|min:8|max:30',
        ];

        $data = $request->validate( $rules );

        return $data;
    }

}
