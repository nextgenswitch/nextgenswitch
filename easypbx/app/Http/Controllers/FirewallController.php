<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\IpBlackList;
use Illuminate\Http\Request;

class FirewallController extends Controller
{
    public function index(){
        $settings = Setting::getSettings('firewall');

        $ipBlackLists = IpBlackList::where('organization_id', auth()->user()->organization_id)->latest()->paginate(10);
        
        if(request()->ajax()){
            IpBlackList::writeIpList();
            return view( 'firewall.ip.table', compact( 'settings', 'ipBlackLists' ) );    
        }
        
        return view( 'firewall.index', compact( 'settings', 'ipBlackLists' ) );
    }

    public function store( Request $request ) {

        $request->validate( ['settings.*' => 'required'] );
        $data = $request->except( '_token' );
        $data['settings']['enable_firewall'] = isset($data['settings']['enable_firewall']) ? 1 : 0;
        
        Setting::writeSettings('firewall', $data['settings']);
        
        return redirect()->route('settings.firewall.index')
                ->with('success_message', __('Firewall settings was successfully changed.'));


    }

    

}
