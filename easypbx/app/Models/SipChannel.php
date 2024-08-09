<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SipChannel extends Model
{
    use HasFactory;


    protected $fillable = [
        'sip_user_id',
        'organization_id',
        'location',
        'expire',
        'ua',
    ];

    public function sipUser(){
        return $this->belongsTo(SipUser::class, 'sip_user_id');
    }
}