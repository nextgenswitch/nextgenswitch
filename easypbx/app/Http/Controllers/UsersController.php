<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;
use Schema;

class UsersController extends Controller {

    /**
     * Display a listing of the users.
     *
     * @return Illuminate\View\View
     */
    public function index( Request $request) {
        // $this->createRoleAndPermisssions();
        User::createRoleAndPermisssions();

        $q       = $request->get( 'q' ) ?: '';
        $perPage = $request->get( 'per_page' ) ?: 10;
        $filter  = $request->get( 'filter' ) ?: '';
        $sort    = $request->get( 'sort' ) ?: '';

    
        $users = User::where( 'organization_id', auth()->user()->organization_id );

        if (  ! empty( $q ) ) {
            $users->where( 'name', 'LIKE', '%' . $q . '%' );
        }

        if (  ! empty( $filter ) ) {
            $filtera = explode( ':', $filter );
            $users->where( $filtera[0], '=', $filtera[1] );
        }

        if (  ! empty( $sort ) ) {
            $sorta = explode( ':', $sort );
            $users->orderBy( $sorta[0], $sorta[1] );
        } else {
            $users->orderBy( 'created_at', 'DESC' );
        }

        if (  ! empty( $request->get( 'csv' ) ) ) {

            $fileName = 'users.csv';
            $users    = $users->get();

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0",
            ];

            $columns = ['name', 'email']; // specify columns if need

            $callback = function () use ( $users, $columns ) {
                $file = fopen( 'php://output', 'w' );
                fputcsv( $file, $columns );

                foreach ( $users as $user ) {

                    foreach ( $columns as $column ) {
                        $row[$column] = $user->{$column};
                    }

                    fputcsv( $file, $row );
                }

                fclose( $file );
            };

            return response()->stream( $callback, 200, $headers );
        }

        $users = $users->paginate( $perPage );

        $users->appends( ['sort' => $sort, 'filter' => $filter, 'q' => $q, 'per_page' => $perPage] );

        
        if ( $request->ajax() ) {
            return view( 'users.table', compact( 'users') );
        }

        return view( 'users.index', compact( 'users' ) );

    }

    /**
     * Show the form for creating a new user.
     *
     * @return Illuminate\View\View
     */
    public function create( Request $request) {

        if ( $request->ajax() ) {
            return view( 'users.form' )->with( ['action' => route( 'users.user.store' ), 'user' => null, 'method' => 'POST'] );
        } else {
            return view( 'users.create' );
        }

    }

    /**
     * Store a new user in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store( Request $request ) {

        $data = $this->getData( $request );

        $data['password'] = Hash::make( $data['password'] );
        $data['organization_id'] = auth()->user()->organization_id;

        $user = User::create( $data );
        
        Artisan::call( 'cache:forget spatie.permission.cache' );
        Artisan::call( 'cache:clear' );

        
        
        $role = Role::findByName($data['role']);
        setPermissionsTeamId($user->organization_id);
        $user->syncRoles($role);

        // $roles = array();
        // $roles[] = $data['role'];

        // $user->syncRoles($roles);

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'users.user.index' )
            ->with( 'success_message', __( 'User was successfully added.' ) );

    }

    /**
     * Show the form for editing the specified user.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit( $id, Request $request ) {

        if(! User::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

        $user           = User::findOrFail( $id );
        $user->password = '';

        
        if ( $request->ajax() ) {
            return view( 'users.form', compact( 'user') )->with( ['action' => route( 'users.user.update', $id ), 'method' => 'PUT'] );
        } else {
            return view( 'users.edit', compact( 'user') );
        }

    }

    public function show($id){

        $user = User::find($id);
        $roles = $user->getRoleNames();

        $temp = array();

        foreach($roles as $name){
            $role = Role::findByName($name);
            $permissions = $role->permissions;
            $temp[$name] = $permissions;
        }
        $user->permissions = $temp;
        
        return $user;

        
        // $permissions = $role->permissions;
        // return $permissions;
    }

    /**
     * Update the specified user in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update( $id, Request $request ) {

        $data           = $this->getData( $request, $id );
        $data['status'] = isset( $data['status'] ) ? $data['status'] : 0;

        if(! User::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();
        
        $user = User::findOrFail( $id );

        if ( isset( $data['password'] ) && $data['password'] !== null && ! empty( $data['password'] ) ) {
            $data['password'] = Hash::make( $data['password'] );
        } else {
            unset( $data['password'] );
        }

        
        if($id !=  auth()->user()->id){
            Artisan::call( 'cache:forget spatie.permission.cache' );
            Artisan::call( 'cache:clear' );
    
            $role = Role::findByName($data['role']);
            setPermissionsTeamId($user->organization_id);
            $user->syncRoles($role);            
        }else
            $data['role'] = auth()->user()->role;


        $user->update( $data );
        
        

        // $roles = array();
        // $roles[] = $data['role'];

        // $user->syncRoles($roles);

        if ( $request->ajax() ) {
            return response()->json( ['success' => true] );
        }

        return redirect()->route( 'users.user.index' )
            ->with( 'success_message', __( 'User was successfully updated.' ) );

    }

    /**
     * Remove the specified user from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy( $id, Request $request ) {
        
            if(! User::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $user = User::findOrFail( $id );

            if($user->hasRole('Super Admin')){
                return response()->json( ['success' => false] );
            }
            

            $user->delete();

            if ( $request->ajax() ) {
                return response()->json( ['success' => true] );
            } else {
                return redirect()->route( 'users.user.index' )
                    ->with( 'success_message', __( 'User was successfully deleted.' ) );
            }

    }

    /**
     * update the specified user for a single field.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function updateField( $id, Request $request ) {

        try {

            if(! User::where('id', $id)->where('organization_id', auth()->user()->organization_id)->exists() )
            return back();

            $user = User::findOrFail( $id );

            $user->update( $request->all() );

            return response()->json( ['success' => true] );

        } catch ( Exception $exception ) {
            return response()->json( ['success' => false] );
        }

    }

    /**
     * update the specified user for a bulk data fields.
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */

    public function bulkAction( Request $request ) {

        try {

            $data = $request->all();
            $ids  = explode( ',', $data['ids'] );

            if ( isset( $data['mass_delete'] ) && $data['mass_delete'] == 1 ) {
                User::whereIn( 'id', $ids )->delete();
            } else {

                foreach ( $data as $field => $val ) {

                    if (  ! in_array( $field, ['ids', '_token', '_method', 'mass_delete'] ) && Schema::hasColumn(  ( new User )->getTable(), $field ) ) {
                        User::whereIn( 'id', $ids )->update( [$field => $val] );
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

        // $email_unique_rule = Rule::unique( 'users' )->where( function ( $query ) use ( $request ) {
        //     return $query->where( 'email', $request->email )->where( 'organization_id', $request->organization_id );
        // } );

        // if ( $id > 0 ) {
        //     $email_unique_rule->ignore( $id );
        // }

        $rules = [
            'email'           => ['required', 'string', 'email', 'unique:users,email'],
            'name'            => 'required|string|min:1|max:255',
            'password'        => 'required|string|min:8|max:30',
            'status'          => 'nullable|string',
            'role'            => 'required|string|min:1|max:255',
        ];

        if ( $id > 0 ) {
            $rules['password'] = 'nullable|string|min:8|max:30';
            $rules['email'] = ['required', 'string', 'email', 'unique:users,email,' . $id];
        }

        $data = $request->validate( $rules );

        return $data;
    }


    public function createRoleAndPermisssions(){
        $permissions = config('enums.permissions');
        
        foreach($permissions as $group => $groupPermissions){
            
            foreach($groupPermissions as $permission){
                if(! Permission::where('name', $permission)->exists()){
                    Permission::create(['name' => $permission]);
                }
            }
        }


        foreach(config('enums.user_roles') as $roleName){
            
            if(!Role::where('name', $roleName)->exists()){
                Role::create(['name' => $roleName, 'organization_id' => null]);    
            }

            $role = Role::where('name', $roleName)->first();

            if(array_key_exists($roleName, $permissions)){
                $role->syncPermissions($permissions[$roleName]);
            }
            
        }
        
    }
}
