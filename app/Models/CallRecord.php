<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallRecord extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'call_records';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'call_id',
                  'dial_call_id',
                  'organization_id',
                  'record_path'
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
     * Get the dialCall for this model.
     *
     * @return App\Models\Call
     */
    public function dialCall()
    {
        return $this->belongsTo('App\Models\Call','dial_call_id');
    }

   



}
