<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IvrAction extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ivr_actions';

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
                  'ivr_id',
                  'digit',
                  'destination_id',
                  'function_id',
                  'voice'
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
     * Get the ivr for this model.
     *
     * @return App\Models\Ivr
     */
    public function ivr()
    {
        return $this->belongsTo('App\Models\Ivr','ivr_id');
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


    public static function ivr_digits($ivr, $ignone_ivr_action = 0){
        $digits = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);

        $query =  self::where('ivr_id', $ivr);

        if($ignone_ivr_action > 0) $query->where('id', '!=', $ignone_ivr_action);

        $existing_digits = $query->pluck('digit')->toArray();

        return array_diff($digits, $existing_digits);
    }


}
