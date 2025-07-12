<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\IpBlackList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['organization_id', 'key', 'value', 'group'];


    public static function getSettings($group)
    {

        $default = [];
        if ($group == 'switch') {
            $default = [
                'udp_listen' => '0.0.0.0:5060',
                'tcp_listen' => '0.0.0.0:5060',
                'tls_listen' => '0.0.0.0:5061',
                'rtp_start' => '10000',
                'rtp_end' => '20000',
                'http_listen' => '127.0.0.1:5001',
            ];
        } else if ($group == 'firewall') {
            $default = [
                'enable_firewall' => 1,
                'failed_attempts_allow' => 10,
                'ban_time' => 600,
                'find_time' => 60,
                'notification_email' => ''
            ];
        }

        // $settings = Setting::where("group", $group)->where('organization_id', self::getOrganization($group))->pluck('value', 'key')->toArray();

        $settings = Setting::where("group", $group)->pluck('value', 'key')->toArray();
        $settings = array_merge($default, $settings);
        return $settings;
    }

    public static function writeSettings($group, $settings)
    {
        foreach ($settings as $key => $val) {
            Setting::updateOrCreate(['organization_id' => self::getOrganization($group), 'key' => $key, 'group' => $group], ['value' => $val, 'organization_id' => self::getOrganization($group), 'key' => $key, 'group' => $group]);
        }
        self::write_settings_json($group);
    }

    public static function getOrganization($group)
    {
        if ($group == 'switch') return 0;
        else return auth()->user()->organization_id;
    }

    public static function getPath($group)
    {
        $path = storage_path('settings');
        if (file_exists($path) == false) @mkdir($path, 0777);

        if ($group == 'switch' || $group == 'firewall') return storage_path('settings/' .  $group . ".json");

        // else if($group == 'firewall') return storage_path('settings/' .  $group . ".json");

        else return  storage_path('settings/' .  $group . "_"  . auth()->user()->organization_id .  ".json");
    }

    public static function get_settings_json($group)
    {
        $path = self::getPath($group);
        if (file_exists($path) == false)
            self::write_settings_json($group);
        return file_get_contents($path);
    }


    public static function write_settings_json($group)
    {
        /*  $arr = Setting::all([
            'key','value','group'
        ])->where( 'organization_id', 0 )
        ->keyBy(function ($item) {

            return $item['group'] . "_" . $item['key'];

        }) // key every setting by its name
        ->transform(function ($setting) {
             return $setting->value; // return only the value
        })->toArray(); // make it an array */

        $arr = [];
        $settings = self::getSettings($group);
        foreach ($settings as $k => $v) {
            $arr[$k] = $v;
        }


        $text = json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        file_put_contents(self::getPath($group), $text);
    }

    public static function writeIpTables($settings)
    {
        $data = file_get_contents(base_path('setup/iptables-rules-sample'));

        foreach ($settings as $k => $setting) {
            if (preg_match('/:(\d+)$/', $setting, $matches)) {
                $port = $matches[1];
                $data = str_replace('%' . $k . '%', $port, $data);
            } else {
                $data = str_replace('%' . $k . '%', $setting, $data);
            }
        }

        $data = str_replace('%updated_at%', Carbon::now()->toDateTimeString(), $data);
        file_put_contents(storage_path('iptables-rules'), $data);
    }

    public static function saveToTempBlock(string $ip): void
    {
        $filePath = storage_path('temp_block.csv');

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return;
        }

        $existingIps = file_exists($filePath)
            ? file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
            : [];

        if (in_array($ip, $existingIps)) {
            return; // already exists, skip
        }

        $existingIps[] = $ip;

        file_put_contents($filePath, implode("\n", $existingIps));
    }


    public static function syncPermanentIpBlockList()
    {
        $ips = IpBlackList::select(['ip', 'subnet'])->get();
        $ipList = [];

        foreach ($ips as $row) {
            if (filter_var($row->ip, FILTER_VALIDATE_IP)) {
                $ipList[] = $row->subnet ? $row->ip . '/' . $row->subnet : $row->ip;
            }
        }

        $filePath = storage_path('permanent_block.csv');
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        if (!empty($ipList)) {
            file_put_contents($filePath, implode("\n", $ipList));
        }
    }
}
