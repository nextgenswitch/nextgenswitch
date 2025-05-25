<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RingGroupCall extends Model
{
    use HasFactory;


    protected $fillable = [
        'call_id',
        'ring_group_id',
        
    ];
}