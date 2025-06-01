<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'applications';

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
                  'code',
                  'destination_id',
                  'function_id',
                  'name',
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
     * Get the function for this model.
     *
     * @return App\Models\Function
     */
    public function func()
    {
        return $this->belongsTo('App\Models\Func','function_id');
    }


}
