<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeGroup extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'time_groups';

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
                  'name',
                  'time_zone',
                  'schedules'
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
     * Get schedule_days in array format
     *
     * @param  string  $value
     * @return array
    */ 
    // public function getSchedulesAttribute($value)
    // {
    //     return explode(',', $value);
    // }

    // public function setSchedulesAttribute($value)
    // {
    //     $this->attributes['schedules'] = !empty($value) ? implode(',',$value) : null;
    // }
    


}
