<?php

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\Call;
use App\Models\Contact;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session('agent_login')) {
            return redirect()->route('agent.login');
        }

        $sip_user = session('sip_user');
        $agent_id = session('agent_id');

        $active_agents = Cache::get('active_agents', []);

        // check exists agent_id in cache
        if (!isset($active_agents[$agent_id])) {
            $active_agents[$agent_id] = $sip_user;
            Cache::put('active_agents', $active_agents, 3600); // Cache for 1 hour
        }

        $call_info = [];
        $sip_user = $active_agents[$agent_id];
        if(isset($sip_user->bridge_call_id) && $sip_user->call_id){
            $call = Call::find($sip_user->call_id);
            $bridge_call = Call::find($sip_user->bridge_call_id);

            $call_info = [
                'call_id' => $call->id,
                'bridge_call_id' => $bridge_call->id,
                'caller_id' => $call->caller_id,
                'destination' => $bridge_call->destination,
                'status' => $call->status,
                'duration' =>  Carbon::now()->diffInSeconds(Carbon::parse($bridge_call->establish_time)),
                'customer' => Contact::customerLookup($call->caller_id, $call->organization_id),
            ];
            
        }


        return view('agents.dashboard', compact('sip_user', 'agent_id', 'call_info'));
    }
}
