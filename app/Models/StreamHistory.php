<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreamHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'stream_id',
        'caller_id',
        'call_id',
        'duration',
        'record_file',
        'transcript',
    ];

    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }
}
