<?php
if (!function_exists('date_time_format')) {

    function date_time_format($date){
        return ($date)?$date->format(config('easypbx.date_time_format')):"";
    }

}

if (!function_exists('duration_format')) {

    function duration_format($duration){
        if ( $duration > 0 ) {
            return Carbon\CarbonInterval::seconds( $duration )->cascade()->forHumans(['short' => true]);
        }

        return '';
    }

}