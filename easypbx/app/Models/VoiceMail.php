<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VoiceRecord;

class VoiceMail extends Model
{
    use HasFactory;
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'voice_record_id',
        'voice_path',
        'transcript',
        'read',
        'caller_id',
        'call_id'
    ];

    public function voiceRecord(){
        return $this->belongsTo(VoiceRecord::class, 'voice_record_id');
    }
}
