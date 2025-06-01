<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Func extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'funcs';

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
                  'func',
                  'func_type',
                  'name',
                  'organization_id'
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

    public static function getFuncList(){
        return Func::where( 'func_type', 0 )->pluck( 'name', 'func' )->toArray();
    }


}
