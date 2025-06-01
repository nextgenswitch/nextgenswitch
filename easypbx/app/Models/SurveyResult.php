<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'call_id',
        'survey_id',
        'caller_id',
        'pressed_key',
        'record_file',
    ];

    public function survey(){
        return $this->belongsTo(Survey::class);
    }

    
}
