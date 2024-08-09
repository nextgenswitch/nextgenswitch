<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtsHistory extends Model
{
    use HasFactory;

    protected $fillable = ['organization_id', 'tts_profile_id', 'type', 'input', 'output'];
}
