<?php

namespace App\Http\Controllers\Agent\Auth;

use App\Http\Controllers\Controller;
use App\Models\SipUser;
use Illuminate\Http\Request;

class LoginController extends Controller {

    public function showLoginForm() {
        return view( 'agents.auth.login' );
    }

    protected function login( Request $request ) {
        $data = $request->validate( [
            'username' => 'required|string',
            'password' => 'required|string',
        ] );

        $sipUser = SipUser::where( 'username', $request->username )->where( 'password', $request->password )->first();

        if ( $sipUser ) {
            session()->flush();
            session( ['agent_login' => true, 'agent' => $sipUser] );

            return redirect()->route( 'agent.dashboard' );
        }

        return redirect()->back()->withErrors( ['username' => ['These credentials do not match our records.']] );
    }

}
