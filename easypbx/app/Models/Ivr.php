<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ivr extends Model
{
    



    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'organization_id',
                  'name',
                  'welcome_voice',
                  'instruction_voice',
                  'invalid_voice',
                  'timeout_voice',
                  'invalid_retry_voice',
                  'timeout_retry_voice',
                  'timeout',
                  'function_id',
                  'destination_id',
                  'max_digit',
                  'max_retry',
                  'end_key',
                  'mode',
                  'intent_analyzer',
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
    

    public function func(){
        return $this->belongsTo( 'App\Models\Func', 'function_id' );
    }

    public function actions(){
        return $this->hasMany('App\Models\IvrAction','ivr_id');
    }

    public function welcomeVoice()
    {
        return $this->belongsTo('App\Models\VoiceFile','welcome_voice');
    }

    public function instructionVoice()
    {
        return $this->belongsTo('App\Models\VoiceFile','instruction_voice');
    }

    public function timeoutVoice()
    {
        return $this->belongsTo('App\Models\VoiceFile','timeout_voice');
    }

    public function invalidVoice()
    { 
        return $this->belongsTo('App\Models\VoiceFile','invalid_voice');
    }

    public function invalidRetryVoice()
    {
        return $this->belongsTo('App\Models\VoiceFile','invalid_retry_voice');
    }

    public function timeoutRetryVoice()
    {
        return $this->belongsTo('App\Models\VoiceFile','timeout_retry_voice');
    }


}
