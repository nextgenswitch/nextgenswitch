<?php

namespace App\Http\Controllers\Agent;

use App\Models\SipUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        if (session('agent_login')) {
            return redirect()->route('agent.dashboard');
        }

        return view('agents.login');
    }

    protected function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $sipUser = SipUser::with('extension')->where('username', $request->username)->where('password', $request->password)->first();

        if ($sipUser) {
            session(['agent_login' => true, 'sip_user' => $sipUser]);

            $active_agents = Cache::get('active_agents', []);
            // remove old entry if exists
            foreach ($active_agents as $key => $agent) {
                if ($agent->id === $sipUser->id) {
                    session(['agent_id' => $key]);
                    return redirect()->route('agent.dashboard');
                }
            }

            $agent_id = uniqid('agent_');
            session(['agent_id' => $agent_id]);
            $active_agents[$agent_id] = $sipUser;
            Cache::put('active_agents', $active_agents, 3600); // Cache for 1 hour

            return redirect()->route('agent.dashboard');
        }

        return redirect()->back()->withErrors(['username' => ['These credentials do not match our records.']]);
    }

    public function logout(Request $request)
    {

        $agent_id = session('agent_id');
        $active_agents = Cache::get('active_agents', []);
        if (isset($active_agents[$agent_id])) {
            $sipUser = $active_agents[$agent_id];

            if (!$sipUser->call_id || !$sipUser->bridge_call_id) {
                unset($active_agents[$agent_id]);
                Cache::put('active_agents', $active_agents, 3600); // Cache for 1 hour
            }
        }
        session()->forget('agent_login');
        session()->forget('agent_id');
        session()->forget('sip_user');
        return redirect()->route('agent.login');
    }
}
