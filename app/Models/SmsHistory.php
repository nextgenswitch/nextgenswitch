<?php

namespace App\Models;

use App\Enums\SmsStatusEnum;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsHistory extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organization_id',
        'trxid',
        'from',
        'to',
        'body',
        'sms_count',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($history) {

            $history->id = Str::uuid();

        });
    }

    protected $casts = [
        'status' => SmsStatusEnum::class
    ];
    
}
