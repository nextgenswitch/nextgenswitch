<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class LicenceManagerController extends Controller {
    private $url        = 'https://license.infosoftbd.com/api/nextgenswitch';
    private $active_url = 'https://license.infosoftbd.com/api/nextgenswitch/active';

    public function index() {
        return view( 'licences.index' );
    }

    public function render( $view ) {
        return view( $view );
    }

    public function store( Request $request ) {

        if ( $request->ajax() ) {

            $rules = [
                'name'    => ['required', 'string'],
                'email'   => ['required', 'email'],
                'phone'   => ['required', 'string'],
                'country' => ['required', 'string'],
            ];

            $validator = Validator::make( $request->all(), $rules );

            if ( $validator->fails() ) {
                $validationErrors = $validator->errors()->toArray();
                return response()->json(['status' => 'error', 'errors' => $this->getErrors($validationErrors)]);
            }

            $data       = $validator->validated();
            $data['ip'] = $request->server( 'SERVER_ADDR' );

            $response = Http::post( $this->url, $data );

            if ( isset( $response['errors'] ) ) {
                return response()->json(['status' => 'error', 'errors' => $this->getErrors($response['errors'])]);
            }

            if ( isset( $response['uid'] ) ) {

                file_put_contents( storage_path( 'licence.json' ), $response );

                return response()->json(['status' => 'success', 'data' => []]);
            }

        }

    }

    public function licenceActive( Request $request ) {

        if ( $request->ajax() ) {

            $rules = [
                'email' => ['required', 'email'],
                'key'   => ['required', 'string'],
            ];

            $validator = Validator::make( $request->all(), $rules );

            if ( $validator->fails() ) {
                $validationErrors = $validator->errors()->toArray();
                return response()->json(['status' => 'error', 'errors' => $this->getErrors($validationErrors)]);
            }

            $data       = $validator->validated();
            $data['ip'] = $request->server( 'SERVER_ADDR' );

            $response = Http::post( $this->active_url, $data );

            if ( isset( $response['errors'] ) ) {
                return response()->json(['status' => 'error', 'errors' => $this->getErrors($response['errors'])]);
            }

            if ( isset( $response['uid'] ) ) {
                file_put_contents( storage_path( 'licence.json' ), $response );

                return response()->json(['status' => 'success', 'data' => []]);
            }

        }

    }

    public function syncLicence() {

        if ( config()->has( 'licence' ) && config( 'licence.uid' ) != "" ) {
            $response = Http::get( $this->url . '/' . config( 'licence.uid' ) );

            if ( isset( $response['uid'] ) ) {
                file_put_contents( storage_path( 'licence.json' ), $response );
                $response = Http::post( "http://" . config( 'settings.switch.http_listen' ) . config( 'easypbx.set_license' ), $response->json() );
                
                // $name = isset($response['brand_name']) ? $response['brand_name'] : env('APP_NAME');

                // putenv("APP_NAME={$name}");
                // $_ENV['APP_NAME'] = $name;
                // config(['app.name' => $name]);

                if ( request()->ajax() ) {
                    return response()->json( [], 200 );
                }

                return redirect()->back();
            }

        }

    }

    public function getErrors( $validationErrors ) {
        

        $errors = [];

        foreach ( $validationErrors as $field => $error ) {

            if ( isset( $error[0] ) ) {
                $errors[$field] = $error[0];
            }

        }

        return $errors;

    }

}
