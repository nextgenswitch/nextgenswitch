<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\QueueStatusEnum;
class Queue extends Model
{
    use HasFactory;


    protected $fillable = [
        'call_queue_id',
        'call_id',
        'organization_id',
        'status',
        'bridge_call_id',
        'duration',
        'waiting_duration',
        'queue_name',
    ];

    protected $casts = [
        'status' => QueueStatusEnum::class,
       
    ];


    public function call(){
        return $this->hasOne(Call::class, 'id', 'call_id');
    }

    public function bridgeCall(){
        return $this->hasOne(Call::class, 'id', 'bridge_call_id');
    }



}
