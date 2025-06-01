<?php

namespace App\Models;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class CampaignSms extends Model
{
    use HasFactory;

    protected $fillable = ['campaign_id', 'sms_history_id', 'contact', 'retry', 'status'];

    protected static function boot() {
        parent::boot();
        static::creating( function ( $call ) {

            $call->id = Str::uuid();

        } );
    }
    
    public function campaign(){
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }


    public function smsHistroy(){
        return $this->belongsTo(SmsHistory::class, 'sms_history_id');
    }
}
