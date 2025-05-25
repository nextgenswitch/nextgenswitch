<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiBot extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ai_bots';

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
                  'organization_id',
                  'name',
                  'llm_provider_id',
                  'tts_profile_id',
                  'stt_profile_id',
                  'resource',
                  'create_support_ticket',
                  'email',
                  'function_id',
                  'destination_id',
                  'voice_id',
                  'listening_tone',
                  'waiting_tone',
                  'inaudible_voice',
                  'call_transfer_tone',
                  'internal_directory',
                  'max_interactions',
                  'max_silince'
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
    

    public function function()
    {
        return $this->belongsTo('App\Models\Func','function_id');
    }

        /**
     * Get the voice for this model.
     *
     * @return App\Models\Voice
     */
    public function welcome_voice()
    {
        return $this->belongsTo('App\Models\VoiceFile','voice_id');
    }

    public function listening_voice()
    {
        return $this->belongsTo('App\Models\VoiceFile','listening_tone');
    }

    public function inaudible_tone()
    {
        return $this->belongsTo('App\Models\VoiceFile','inaudible_voice');
    }

    public function waiting_voice()
    {
        return $this->belongsTo('App\Models\VoiceFile','waiting_tone');
    }

    public function call_transfer_tone()
    {
        return $this->belongsTo('App\Models\VoiceFile','call_transfer_tone');
    }

    public function llm_provider()
    {
        return $this->belongsTo('App\Models\TtsProfile','llm_provider_id');
    }



   

    
}
