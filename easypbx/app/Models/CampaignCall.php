<?php

namespace App\Models;

use App\Enums\CallStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CampaignCall extends Model
{
    use HasFactory;

    protected $fillable = ['campaign_id', 'call_id', 'tel', 'retry', 'status', 'duration', 'completed'];


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
    
}
