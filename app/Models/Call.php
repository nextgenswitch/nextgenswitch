<?php

namespace App\Models;

use App\Enums\CallStatusEnum;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Call extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'calls';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    protected $keyType    = 'string';
    public $incrementing  = false;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_call_id',
        'organization_id',
        'caller_id',
        'channel',
        'destination',
        'sip_user_id',
        'status',
        'connect_time',
        'ringing_time',
        'establish_time',
        'disconnect_time',
        'disconnect_code',
        'duration',
        'user_agent',
        'uas',
    ];

    protected $guarded = [];

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
    protected $casts = [
        'status'          => CallStatusEnum::class,
        'connect_time'    => 'datetime',
        'ringing_time'    => 'datetime',
        'establish_time'  => 'datetime',
        'disconnect_time' => 'datetime',

    ];

    protected static function boot() {
        parent::boot();
        static::creating( function ( $call ) {

            $call->id = Str::uuid();

        } );
    }

    /**
     * Get the organization for this model.
     *
     * @return App\Models\Organization
     */
    public function organization() {
        return $this->belongsTo( 'App\Models\Organization', 'organization_id' );
    }

    public function bridgeCall() {
        return $this->hasMany( 'App\Models\Call', 'parent_call_id' );
    }

    /**
     * Get the sipUser for this model.
     *
     * @return App\Models\SipUser
     */
    public function sipUser() {
        return $this->belongsTo( 'App\Models\SipUser', 'sip_user_id' );
    }

    public function records() {
        return $this->hasMany( 'App\Models\CallRecord', 'call_id' );
    }

    public function getHumanReadableDurationAttribute() {

        if ( $this->duration > 0 ) {
            return CarbonInterval::seconds( $this->duration )->cascade()->forHumans();
        }

        return '';
    }

    

}
