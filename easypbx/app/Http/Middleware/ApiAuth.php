<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use App\Models\ApiAccessLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;

class ApiAuth {

    const AUTH_HEADER = 'X-Authorization';
    const AUTH_SECRET = 'X-Authorization-Secret';

    /**
     * Handle the incoming request
     *
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Contracts\Routing\ResponseFactory|mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function handle( Request $request, Closure $next ): Response {
    
        $header = $request->header(self::AUTH_HEADER);
        $secret = $request->header(self::AUTH_SECRET);
        $apiKey = ApiKey::getByKey($header);
       
        if ($apiKey instanceof ApiKey && $this->testSecretKey($secret, $apiKey)) {
            $this->logAccessEvent($request, $apiKey);
            $data   = $request->all();
            $data['organization_id'] = $apiKey->organization_id;
            $request->replace( $data );
            return $next($request);
        }

        return response([
            'errors' => [[
                'message' => 'Unauthorized'
            ]]
        ], 401);
        
      
      
    }

    /**
     * Test the secret key, but only if it is configured to be used
     *
     * @param string $secret
     * @param ApiKey $apiKey
     * @return boolean
     */
    public function testSecretKey($secret, ApiKey $apiKey) {
        if(config('apikey.enable_secret_key') === true) {
            if($secret && Hash::check($secret, $apiKey->secret)) {
                if (Hash::needsRehash($apiKey->secret)) {
                    $apiKey->secret = $secret;
                    $apiKey->save(); // The ApiKeyObserver will rehash it
                }
                // configured and passes
                return true;
            } else {
                // configured, but failed
                return false;
            }
        } else {
            // not configured
            return true;
        }
    }

    /**
     * Log an API key access event
     *
     * @param Request $request
     * @param ApiKey  $apiKey
     */
    protected function logAccessEvent(Request $request, ApiKey $apiKey)
    {
        $event = new ApiAccessLog;
        $event->organization_id = $apiKey->organization_id;
        $event->api_key_id = $apiKey->id;
        $event->ip_address = $request->ip();
        $event->url        = $request->fullUrl();
        $event->save();
    }
}

/* class ApiAuth {
   
    public function handle( Request $request, Closure $next ): Response {

        $api_key = $request->header( 'apikey' );

        if (  ! isset( $api_key ) || empty( $api_key ) ) {
            return response()->json( [
                'status'  => false,
                'message' => 'API key is required',
                'data'    => [],
            ], 400 );
        }

        $api = Api::where( 'key', $api_key )->where( 'status', 1 )->first();

        if (  ! $api ) {
            return response()->json( [
                'status'  => false,
                'message' => 'Api Key does not match.',
                'data'    => [],
            ], 401 );
        }

        $data   = $request->all();
        $errors = [];

        foreach ( $data as $key => $value ) {
            $data[$key] = $value;
            // $data[$key] = base64_decode( $value );
        }

        if ( count( $errors ) > 0 ) {
            return response()->json( [
                'status'  => false,
                'message' => 'The provided parameters are not a valid encrypted value',
                'data'    => $errors,
            ], 400 );
        }

        $data['organization_id'] = $api->organization_id;
        $request->replace( $data );

        return $next( $request );
    }

}
 */