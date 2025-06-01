<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;


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
    protected $casts = [ 'data' => 'object'];
    
    /**
     * Get the user for this model.
     *
     * @return App\Models\User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User','notifiable_id');
    }


  
    

}
