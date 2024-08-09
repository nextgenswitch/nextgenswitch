<?php

namespace App\Http\Controllers;

use App\Models\Func;
use Illuminate\Http\Request;
use App\Http\Traits\FuncTrait;
use App\Http\Controllers\Api\FunctionCall;
use App\Http\Controllers\Api\VoiceResponse;

class CallingController extends Controller {
    use FuncTrait;

    public function index() {

        $destinations = [];
        $functions    = Func::getFuncList();

        return view( 'calling.index', compact( 'destinations', 'functions' ) );
    }

    public function calling( Request $request ) {
        $data = $request->validate( [
            'to'             => 'required',
            'from'           => 'required',
            'function_id'    => 'required',
            'destination_id' => 'required',
        ] );

        $func = Func::where( 'func', $data['function_id'] )->first();

        if ( $func ) {
            $call = ['to' => $data['to'], "organization_id" => auth()->user()->organization_id, "from" => $data['from'], 'response' => route( 'api.func_call', ['func_id' => $func->id, 'dest_id' => $data['destination_id']] )];
            
            // return $call;

            return FunctionCall::send_call( $call );
        } else {
            return ["error" => true, 'error_code' => '', 'message' => "function not available"];
        }

    }

    public function dialing(Request $request){
        $data = $request->validate([
            'to' => 'required',
            'callback' => 'required',
        ]);

        $response = new VoiceResponse();
        $response->dial($data['callback']);
        
        

        $payload = [
            'to' => $data['to'], 
            'from'=>$data['callback'],
            "organization_id" => auth()->user()->organization_id,
            'responseXml' => $response->xml()
        ];
       // return $payload;

        return FunctionCall::send_call( $payload );
    }

    public function destinations( $function ) {

        if ( request()->ajax() ) {
            return $this->dist_by_function( $function );
        }

        die();

    }

}
