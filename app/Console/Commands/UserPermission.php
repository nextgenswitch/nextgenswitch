<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Organization;
class UserPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'easypbx:permission {action} {userid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::createRoleAndPermisssions();
        
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        $action = $this->argument('action');

       $this->{$action}($this->argument('userid')); 

    }

    function superAdmin($user_id){    
        try {
            $role = Role::findByName('Super Admin');
        }catch(\Exception $e) {
            $role = Role::create(['name' => 'Super Admin', 'organization_id' => null]);    
        }
        $user = User::find($user_id);
        setPermissionsTeamId($user->organization_id);
        $user->syncRoles($role);
    }

    function saasAdmin($user_id){    
        try {
            $role = Role::findByName('SAAS Admin');
        }catch(\Exception $e) {
            $role = Role::create(['name' => 'SAAS Admin', 'organization_id' => null]);    
        }
        $user = User::find($user_id);
        setPermissionsTeamId($user->organization_id);
        $user->assignRole($role);
    }

    function admin($user_id){
        try {
            $role = Role::findByName('Admin');
        }catch(\Exception $e) {
            $role = Role::create(['name' => 'Admin', 'organization_id' => null]);  
        }    

        $user = User::find($user_id);
        setPermissionsTeamId($user->organization_id);
        $user->syncRoles($role);


        // Permission::create(['name'=>'admin.*']);
        // $role->syncPermissions('admin.*');
        // $user = User::find($user_id);     
        // $user->removeRole('Admin');
        // $user->syncRoles();
        // var_dump($user->getRoleNames());
        // setPermissionsTeamId($user->organization_id);
        // $user->syncRoles($role);
    }


    function manager($user_id){    
        try {
            $role = Role::findByName('Manager');
        }catch(\Exception $e) {
            $role = Role::create(['name' => 'Manager', 'organization_id' => null]);    
        }
        $role->syncPermissions('admin.extension.*');

        $user = User::find($user_id);
        setPermissionsTeamId($user->organization_id);
        $user->syncRoles($role);
    }

    function agent($user_id){    
        try {
            $role = Role::findByName('Agent');
        }catch(\Exception $e) {
            $role = Role::create(['name' => 'Agent', 'organization_id' => null]);    
        }
        $role->syncPermissions('admin.monitoring.*');

        $user = User::find($user_id);
        setPermissionsTeamId($user->organization_id);
        $user->syncRoles($role);
    }

}
