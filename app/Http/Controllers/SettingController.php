<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\IpBlackList;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index($group)
    {

        //  if(auth()->user()->isSuperAdmin == false && Setting::getOrganization($group) == 0) return redirect()->back();

        $settings = Setting::getSettings($group);
        return view('settings.index', compact('settings', 'group'));
    }





    public function store(Request $request)
    {
        $request->validate(['settings.*' => 'required']);

        $data = $request->except('_token');

        if ($data['group'] == 'firewall') {
            $data['settings']['enable_firewall'] = isset($data['settings']['enable_firewall']) ? 1 : 0;
        }

        if ($data['group'] == 'switch') {
            // return $data['settings'];

            Setting::writeIpTables($data['settings']);
        }

        Setting::writeSettings($data['group'], $data['settings']);

        return redirect()->route('settings.setting.index', [$data['group']])
            ->with('success_message', __('Settings was successfully changed.'));
    }
}
