<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueueCall extends Model
{
    use HasFactory;


    protected $fillable = [
        'call_id',
        'organization_id',
        'queue_name',
        'parent_call_id',
    ];
}