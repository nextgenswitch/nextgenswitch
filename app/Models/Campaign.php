<?php

namespace App\Models;

use App\Models\Func;
use App\Models\Contact;
use App\Models\CampaignCall;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    // protected $table = 'campaigns';

    /**
    * The database primary key value.
    *
    * @var string
    */
    // protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'organization_id',
                  'name',
                  'from',
                  'contact_groups',
                  'status',
                  'max_retry',
                  'call_limit',
                  'timezone',
                  'start_at',
                  'end_at',
                  'schedule_days',
                  'total_sent',
                  'function_id',
                  'destination_id',
                  'total_successfull',
                  'total_failed',
                  'sms_history_id',
                  'on_queue'
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_at','end_at'];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
    
    /**
     * Get the user for this model.
     *
     * @return App\Models\User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    /**
     * Get the voiceFile for this model.
     *
     * @return App\Models\VoiceFile
     */
    public function func(){
        return $this->belongsTo( 'App\Models\Func', 'function_id' );
    }

    /**
     * get the contact_groups.
     *
     * @param  string  $value
     * @return array
     */
    public function getContactGroupsAttribute($value)
    {
        return explode(',', $value);
    }

    /**
     * Set the contact_groups.
     *
     * @param  string  $value
     * @return void
    */ 

    public function setContactGroupsAttribute($value)
    {
        $this->attributes['contact_groups'] = !empty($value) ? implode(',',$value) : null;
    }
    
  
    /**
     * Get schedule_days in array format
     *
     * @param  string  $value
     * @return array
    */ 
     public function getScheduleDaysAttribute($value)
    {
        return explode(',', $value);
    }

    public function setScheduleDaysAttribute($value)
    {
        $this->attributes['schedule_days'] = !empty($value) ? implode(',',$value) : null;
    }
    
    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    // public function getCreatedAtAttribute($value)
    // {
    //     return \DateTime::createFromFormat('j/n/Y g:i A', $value);
    // }

    /**
     * Get updated_at in array format
     *
     * @param  string  $value
     * @return array
     */
    // public function getUpdatedAtAttribute($value)
    // {
    //     return \DateTime::createFromFormat('j/n/Y g:i A', $value);
    // }

    public function getIsSmsAttribute(){
        $function = Func::find( $this->function_id );
        return $function->func == 'sms' ? true : false;
    }


    public static function inTime( $campaign ) {
        $start = $campaign->start_at;
        $end = $campaign->end_at;
        $timezone = $campaign->timezone;
        $days = $campaign->schedule_days;

        
        $convertedDateTime = now()->tz( $timezone );
       
        $currentDay = $convertedDateTime->format( 'D' );

        $inTime = $convertedDateTime->between( $start, $end );

        if ( $inTime && $days != null ) {
            $inTime = in_array( strtolower( $currentDay ), $days );
        }

      //  $inTime = self::inTime( $campaign1->start_at, $campaign1->end_at, $campaign1->timezone, $campaign1->schedule_days );
      
        return $inTime;
    }

    function inTimeCheck( $start, $end, $timezone, $days = null ): bool {
        $convertedDateTime = now()->tz( $timezone );

        $currentDay = $convertedDateTime->format( 'D' );

        $inTime = $convertedDateTime->between( $start, $end );

        if ( $inTime && $days != null ) {
            $inTime = in_array( strtolower( $currentDay ), $days );
        }

        return $inTime;
    }

    public static function  getCallContacts( $campaign ): array | object {
        return array_values( array_diff( Contact::getContacts($campaign->contact_groups)->toArray(), CampaignCall::getProcessedCoctacts($campaign)->toArray() ) );      
    }

    

}
