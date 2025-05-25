<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpBlackList extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ip_black_lists';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'organization_id',
                  'title',
                  'ip',
                  'subnet'
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
    
    /**
     * Get the organization for this model.
     *
     * @return App\Models\Organization
     */
    public function organization()
    {
        return $this->belongsTo('App\Models\Organization','organization_id');
    }

    public static function writeIpList(){
        $ips = self::where('organization_id', auth()->user()->organization_id)->get();
        $lines = '';
        foreach($ips as $ip){
            $lines .= $ip->ip . "/" . $ip->subnet . "\n";
        }

        file_put_contents(storage_path('settings/ip_block_list.txt'), $lines);
    }


}
