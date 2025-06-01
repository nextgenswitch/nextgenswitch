<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiAssistantCall extends Model
{
    use HasFactory;
    protected $fillable = ['organization_id', 'call_id', 'ai_assistant_id', 'caller_id'];

    public function ai_assistant()
    {
        return $this->belongsTo('App\Models\AiBot','ai_assistant_id');
    }
}
