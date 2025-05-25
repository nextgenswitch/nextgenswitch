<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiAccessLog extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'api_access_logs';



    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'organization_id',
                  'api_key_id',
                  'ip_address',
                  'url',
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


}
