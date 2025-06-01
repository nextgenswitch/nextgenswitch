<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallLeg extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'call_legs';

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
                  'call_id',
                  'channel',
                  'destination',
                  'sip_user_id',
                  'call_status',
                  'connect_time',
                  'ringing_time',
                  'establish_time',
                  'disconnect_time',
                  'duration'
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
     * Get the call for this model.
     *
     * @return App\Models\Call
     */
    public function call()
    {
        return $this->belongsTo('App\Models\Call','call_id');
    }

    /**
     * Get the sipUser for this model.
     *
     * @return App\Models\SipUser
     */
    public function sipUser()
    {
        return $this->belongsTo('App\Models\SipUser','sip_user_id');
    }


}
