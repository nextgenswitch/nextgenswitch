<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Script;

class DialerCampaign extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dialer_campaigns';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'agents',
                  'organization_id',
                  'contact_groups',
                  'description',
                  'end_at',
                  'end_date',
                  'name',
                  'schedule_days',
                  'start_at',
                  'timezone',
                  'status',
                  'script_id',
                  'form_id'
              ];

    public function script(){
        return $this->belongsTo(Script::class, 'script_id');
    }

    public function form(){
        return $this->belongsTo(CustomForm::class, 'form_id');
    }
    

     /**
     * get the contact_groups.
     *
     * @param  string  $value
     * @return array
     */
    public function getAgentsAttribute($value)
    {
        return explode(',', $value);
    }

    /**
     * Set the contact_groups.
     *
     * @param  string  $value
     * @return void
    */ 

    public function setAgentsAttribute($value)
    {
        $this->attributes['Agents'] = !empty($value) ? implode(',', $value) : null;
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
        $this->attributes['contact_groups'] = !empty($value) ? implode(',', $value) : null;
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
    
}
