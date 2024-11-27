<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['organization_id', 'key', 'value','group'];


    public static function getSettings($group){

        $default = [];
        if($group == 'switch'){
            $default = [
                'udp_listen'=>'0.0.0.0:5060',
                'tcp_listen'=>'0.0.0.0:5060',
                'tls_listen'=>'0.0.0.0:5061',
                'rtp_start'=>'10000',
                'rtp_end'=>'20000',
                'http_listen'=>'127.0.0.1:5001',
            ];
        }

        else if($group == 'firewall'){
            $default = [
                'enable_firewall' => 1,
                'failed_attempts_allow' => 3,
                'ban_time' => 3,
                'find_time' => 300,
                'notification_email' => ''
            ];
        }

        $settings = Setting::where("group",$group)->where( 'organization_id', self::getOrganization($group) )->pluck( 'value', 'key')->toArray();
        $settings = array_merge( $default,$settings); 
        return $settings;
    }

    public static function writeSettings($group,$settings){
        foreach ($settings as $key => $val ) {
            Setting::updateOrCreate( ['organization_id' => self::getOrganization($group), 'key' => $key,'group'=>$group], ['value' => $val,'organization_id' => self::getOrganization($group), 'key' => $key,'group'=>$group] );
        }
        self::write_settings_json($group);

        
    }

    public static function getOrganization($group){
        if($group == 'switch') return 0;
        else return auth()->user()->organization_id;
    }

    public static function getPath($group){
        $path = storage_path('settings');
        if(file_exists($path) == false) @mkdir($path,0777);
        
        if($group == 'switch' || $group == 'firewall') return storage_path('settings/' .  $group . ".json");
        
        // else if($group == 'firewall') return storage_path('settings/' .  $group . ".json");

        else return  storage_path('settings/' .  $group . "_"  . auth()->user()->organization_id .  ".json");
    }

    public static function get_settings_json($group){
        $path = self::getPath($group);
        if(file_exists($path) == false)
         self::write_settings_json($group);
        return file_get_contents($path);

    }


    public static function write_settings_json($group){
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
        foreach($settings as $k=>$v){
            $arr[ $k ] = $v;
        }


        $text = json_encode($arr,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        file_put_contents(self::getPath($group),$text);
    }

    public static function writeIpTables($settings){
        $data = file_get_contents(storage_path('settings/iptables-rules-sample.txt'));
        
        foreach($settings as $k => $setting){
            if (preg_match('/:(\d+)$/', $setting, $matches)) {
                $port = $matches[1];
                $data = str_replace('%' . $k . '%', $port, $data);
            } else {
                $data = str_replace('%' . $k . '%', $setting, $data);
            }
        }

        $data = str_replace('%updated_at%', Carbon::now()->toDateTimeString(), $data);
        file_put_contents(storage_path('settings/iptables-rules.txt'), $data);
    }

}
