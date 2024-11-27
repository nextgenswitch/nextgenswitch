<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CallParking;

class CallParkingLog extends Model
{
    use HasFactory;

    protected $fillable = ['call_id', 'organization_id', 'call_parking_id', 'from', 'to', 'parking_no'];

    public function callParking(){
        return $this->hasOne(CallParking::class,'id','call_parking_id');
    }

    public function call(){
        return $this->hasOne(Call::class, 'id', 'call_id');
    }
}
