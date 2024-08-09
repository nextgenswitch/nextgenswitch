<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsProfile extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sms_profiles';

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
                  'default',
                  'name',
                  'options',
                  'organization_id',
                  'provider',
                  'status'
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


}
