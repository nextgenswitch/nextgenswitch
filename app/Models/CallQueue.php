<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CallQueueExtension;

class CallQueue extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'call_queues';



    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'extension_id',
        'join_extension_id',
        'agent_announcemnet',
        'cid_name_prefix',
        'description',
        'join_announcement',
        'join_empty',
        'leave_when_empty',
        'member_timeout',
        'music_on_hold',
        'organization_id',
        'queue_callback',
        'queue_timeout',
        'record',
        'retry',
        'ring_busy_agent',
        'service_level',
        'strategy',
        'timeout_priority',
        'wrap_up_time',
        'function_id',
        'destination_id',
        'agent_function_id',
        'agent_destination_id',
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function queueExtensions(){
        return $this->hasMany(CallQueueExtension::class, 'call_queue_id');
    }

    public function func() {
        return $this->belongsTo( 'App\Models\Func', 'function_id' );
    }

    public function agentFunc() {
        return $this->belongsTo( 'App\Models\Func', 'agent_function_id' );
    }

    public function extension()
    {
        return $this->belongsTo('App\Models\Extension','extension_id');
    }

    public function joinExtension()
    {
        return $this->belongsTo('App\Models\Extension','join_extension_id');
    }

    public function joinAnnouncement()
    {
        return $this->belongsTo('App\Models\VoiceFile','join_announcement');
    }

    public function agentAnnouncement()
    {
        return $this->belongsTo('App\Models\VoiceFile','agent_announcemnet');
    }
    public function musicOnHold()
    {
        return $this->belongsTo('App\Models\VoiceFile','music_on_hold');
    }

    public function getExtensionsAttribute()
    {
        $extensionlist = CallQueueExtension::where("call_queue_id",$this->id)->get();
        $extensions = [];        
        foreach($extensionlist as $qextension){
           // info($qextension);
            $qextension->extension->priority = $qextension->priority;
            $qextension->extension->allow_diversion = $qextension->allow_diversion;
            $qextension->extension->last_ans = ($qextension->last_ans)?$qextension->last_ans->diffInSeconds(now()):1000;
            $qextension->extension->last_dial = ($qextension->last_dial)?$qextension->last_dial->diffInSeconds(now()):1000;
            
            if($qextension->member_type == 1 || $qextension->dynamic_queue == 1)
                $extensions[] = $qextension->extension;                   
        }
        return $extensions;
    }


    
    
 



}
