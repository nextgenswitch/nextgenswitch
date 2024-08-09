<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyOrganization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        

        if(in_array($request->route()->getActionMethod(), ['edit', 'update'])){
            $params = $request->route()->originalParameters();
            if(is_array($params)){
                $param = reset($params);
    
                if($param){
                    
                    $controller = class_basename($request->route()->getControllerClass());
                    $model = Str::singular(str_replace('Controller', '', $controller));
                    $model =  'App\\Models\\' . ucfirst($model);
                    
                    if(! $model::where('id', $param)->where('organization_id', auth()->user()->organization_id)->exists() )
                        return back();

                }
            }
        }

        return $next($request);
    }

    
}
