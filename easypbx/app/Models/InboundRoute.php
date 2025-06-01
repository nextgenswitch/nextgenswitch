<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboundRoute extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inbound_routes';

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
        'name',
        'organization_id',
        'did_pattern',
        'cid_pattern',
        'function_id',
        'destination_id',
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
     * Get the function for this model.
     *
     * @return App\Models\Function
     */
    public function func() {
        return $this->belongsTo( 'App\Models\Func', 'function_id' );
    }

}
