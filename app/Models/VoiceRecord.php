<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoiceRecord extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'voice_records';

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
                    'voice_id',
                    'is_transcript',
                    'is_create_ticket',
                    'play_beep',
                    'phone',
                    'email'
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
     * Get the voice for this model.
     *
     * @return App\Models\Voice
     */
    public function voice()
    {
        return $this->belongsTo('App\Models\VoiceFile','voice_id');
    }


}
