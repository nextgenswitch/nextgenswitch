<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TtsProfile extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tts_profiles';

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
                  'name',
                  'organization_id',
                  'provider',
                  'language',
                  'model',
                  'name',
                  'config',
                  'type',
                  'is_default'
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
    

    public function organization(){
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    // public function getConfigAttribute($config){
    //     return json_decode($config);
    // }

}
