<?php

namespace App\Models;

use App\Models\Trunk;
use Illuminate\Database\Eloquent\Model;

class OutboundRoute extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'outbound_routes';

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
        'priority',
        'is_active',
        'name',
        'organization_id',
        'pattern',
        'trunk_id',
        'pin_list_id',
        'function_id',
        'destination_id',
        'type',
        'record'
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
    protected $casts = [
        'trunk_id' => 'array',
    ];

    /**
     * Get the organization for this model.
     *
     * @return App\Models\Organization
     */
    public function organization() {
        return $this->belongsTo( 'App\Models\Organization', 'organization_id' );
    }

    /**
     * Get the trunk for this model.
     *
     * @return App\Models\Trunk
     */
    public function trunk() {
        return $this->belongsTo( 'App\Models\Trunk', 'trunk_id' );
    }

    /**
     * Get the function for this model.
     *
     * @return App\Models\Function
     */
    public function func() {
        return $this->belongsTo( 'App\Models\Func', 'function_id' );
    }

    public function getPatternAttribute( $value ) {
        return json_decode( $value );
    }

    public function getTrunksAttribute( $value ) {

        if ( is_array( $this->trunk_id ) && sizeof( $this->trunk_id ) > 0 ) {
            $trunks = implode( ',', $this->trunk_id );
            $trunks = Trunk::whereIn( 'id', $this->trunk_id )->orderByRaw( "FIELD(id,{$trunks})" )->get();
        } else {
            $trunks = Trunk::where( 'id', $this->trunk_id )->get();
        }

        return $trunks;
    }

    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getCreatedAtAttribute( $value ) {
        return \DateTime::createFromFormat( 'j/n/Y g:i A', $value );
    }

    /**
     * Get updated_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getUpdatedAtAttribute( $value ) {
        return \DateTime::createFromFormat( 'j/n/Y g:i A', $value );
    }

}
