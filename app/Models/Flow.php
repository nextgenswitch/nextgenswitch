<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flow extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'flows';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    protected $keyType    = 'string';
    public $incrementing  = false;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'matched_action',
        'unmatched_action',
        'match_type',
        'matched_value',
        'organization_id',
        'voice_file',
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
     * Get the matchAction for this model.
     *
     * @return App\Models\MatchAction
     */
    public function voiceFile() {
        return $this->belongsTo( 'App\Models\VoiceFile', 'voice_file' );
    }

    /**
     * Get the organization for this model.
     *
     * @return App\Models\Organization
     */
    public function organization() {
        return $this->belongsTo( 'App\Models\Organization', 'organization_id' );
    }

}
