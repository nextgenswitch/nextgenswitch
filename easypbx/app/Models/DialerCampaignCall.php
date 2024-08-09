<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\CarbonInterval;

class DialerCampaignCall extends Model
{
    use HasFactory;

    protected $fillable = ['dialer_campaign_id', 'call_id', 'tel', 'retry', 'status', 'duration', 'form_data'];

    protected static function boot() {
        parent::boot();
        static::creating( function ( $call ) {

            $call->id = Str::uuid();

        } );
    }

    public function getHumanReadableDurationAttribute() {

        if ( $this->duration > 0 ) {
            return CarbonInterval::seconds( $this->duration )->cascade()->forHumans();
        }

        return '';
    }

}
