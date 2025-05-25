<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeCondition extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'time_conditions';

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
                  'time_group_id',
                  'matched_function_id',
                  'matched_destination_id',
                  'function_id',
                  'destination_id'
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
     * Get the timeGroup for this model.
     *
     * @return App\Models\TimeGroup
     */
    public function timeGroup()
    {
        return $this->belongsTo('App\Models\TimeGroup','time_group_id');
    }

    /**
     * Get the matchedFunction for this model.
     *
     * @return App\Models\MatchedFunction
     */
    public function matchedFunc()
    {
        return $this->belongsTo('App\Models\Func','matched_function_id');
    }



    /**
     * Get the function for this model.
     *
     * @return App\Models\Function
     */
    public function func()
    {
        return $this->belongsTo('App\Models\Func','function_id');
    }




}
