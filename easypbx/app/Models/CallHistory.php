<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\CallStatusEnum;
class CallHistory extends Model
{
    use HasFactory;


    protected $fillable = [
        'call_id',
        'bridge_call_id',        
        'organization_id',
        'status',
        'duration',
        'record_file',
    ];

    protected $casts = [
        'status' => CallStatusEnum::class,       
    ];


    public function call(){
        return $this->hasOne(Call::class, 'id', 'call_id');
    }

    public function bridgeCall(){
        return $this->hasOne(Call::class, 'id', 'bridge_call_id');
    }

    
}
