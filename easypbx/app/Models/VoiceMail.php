<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'extension_id',
        'voice_path',
        'transcript',
        'read',
    ];

    public function extension(){
        return $this->belongsTo(Extension::class, 'extension_id');
    }
}
