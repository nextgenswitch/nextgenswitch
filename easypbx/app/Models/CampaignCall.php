<?php

namespace App\Models;

use App\Enums\CallStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CampaignCall extends Model
{
    use HasFactory;

    protected $fillable = ['campaign_id', 'call_id', 'tel', 'retry', 'status', 'duration', 'sms_history_id'];


    protected static function boot() {
        parent::boot();
        static::creating( function ( $call ) {

            $call->id = Str::uuid();

        } );
    }

    public function call(){
        return $this->belongsTo(Call::class, 'call_id');
    }

    public function campaign(){
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }



    protected $casts = [
        'status' => CallStatusEnum::class
    ];


    public static function getProcessedCoctacts($campaign){
        return   self::where( 'campaign_id', $campaign->id )
            ->pluck( 'tel' );
    }

    public static function getRetryCoctacts($campaign){
        return   self::where( 'campaign_id', $campaign->id )
        ->where( 'retry', '<', $campaign->max_retry )
        ->where( 'status', '>', CallStatusEnum::Disconnected->value )
        ->pluck( 'tel' )->toArray();
    }

    public static function activeCallCount($campaign){
        return self::where( 'campaign_id', $campaign->id )->where( 'status', '<', CallStatusEnum::Disconnected->value )->count();
    }
    
}
