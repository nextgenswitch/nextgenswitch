<?php

namespace App\Http\Traits;
use App\Models\Announcement;
use App\Models\CallQueue;
use App\Models\CustomFunc;
use App\Models\Extension;
use App\Models\Ivr;
use App\Models\RingGroup;
use App\Models\Sms;
use App\Models\TimeCondition;
use App\Models\Survey;
use App\Models\AiBot;
use App\Models\VoiceRecord;

trait FuncTrait {

    public function dist_by_function( $func, $ivr = 0, $return_array = false ) {
        $data = [];
        $orid = auth()->user()->organization_id;

        $html = '<option value=""> Select destination </option>';

        if ( $func == 'extension' ) {
            $data = Extension::where( 'organization_id', $orid )->where( 'extension_type', '1' )->pluck( 'name', 'id' )->toArray();
            $html = '<option value=""> ' . __( "Select extension" ) . ' </option>';
        } elseif ( $func == 'call_queue' ) {
            $data = CallQueue::where( 'organization_id', $orid )->pluck( 'name', 'id' )->toArray();
            $html = '<option value=""> Select call queue </option>';
        } elseif ( $func == 'ring_group' ) {
            $data = RingGroup::where( 'organization_id', $orid )->pluck( 'description', 'id' )->toArray();
            $html = '<option value=""> Select call Ring Group </option>';
        } elseif ( $func == 'announcement' ) {
            $data = Announcement::where( 'organization_id', $orid )->pluck( 'name', 'id' )->toArray();
            $html = '<option value=""> Select Announcement </option>';
        } elseif ( $func == 'ivr' ) {
            $data = Ivr::where( 'organization_id', $orid )->pluck( 'name', 'id' )->toArray();
            $html = '<option value=""> Select ivr </option>';
        } elseif ( $func == 'voice_record' ) {
            $data = VoiceRecord::where( 'organization_id', $orid )->pluck( 'name', 'id' )->toArray();
            $html = '<option value=""> Select Voice Record Profile </option>';
        } elseif ( $func == 'custom_function' ) {
            $data = CustomFunc::where( 'organization_id', $orid )->pluck( 'name', 'id' )->toArray();
            $html = '<option value=""> Select custom function </option>';
        } elseif ( $func == 'terminate_call' ) {
            $data = [1 => 'Hangup'];
            $html = '';
        }elseif ( $func == 'sms' ) {
            $data = Sms::where( 'organization_id', $orid )->pluck( 'title', 'id' )->toArray();
            $html = '<option value=""> Select SMS Content</option>';
        }elseif ( $func == 'time_condition' ) {
            $data = TimeCondition::where( 'organization_id', $orid )->pluck( 'name', 'id' )->toArray();
            $html = '<option value=""> Select Time Condition</option>';
        }elseif ( $func == 'call_survey' ) {
            $data = Survey::where( 'organization_id', $orid )->pluck( 'name', 'id' )->toArray();
            $html = '<option value=""> Select Call Survey</option>';
        }elseif ( $func == 'call_parking' ) {
            $data = CallParking::where( 'organization_id', $orid )->pluck( 'name', 'id' )->toArray();
            $html = '<option value=""> Select Call Parking</option>';
        }
        elseif($func == 'ai_assistant'){
            $data = AiBot::where('organization_id', $orid)->pluck('name', 'id')->toArray();
            $html = '<option value=""> Select AI Assistant</option>';
        }


        if ( $return_array ) {
            return $data;
        }

        if ( count( $data ) > 0 ) {

            foreach ( $data as $key => $value ) {
                $html .= "<option value='$key'> $value  </option>";
            }

        }

        return $html;

    }

}
