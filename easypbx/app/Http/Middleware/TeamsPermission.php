<?php
namespace App\Http\Middleware;

class TeamsPermission
{
    
    public function handle($request, \Closure $next){

        // other custom ways to get team_id
        if(!empty(auth()->user())){
        
            // `getTeamIdFromToken()` example of custom method for getting the set team_id 
            setPermissionsTeamId(auth()->user()->organization_id);
        }

        
        return $next($request);
    }
}