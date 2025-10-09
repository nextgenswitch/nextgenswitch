<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extension extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'extensions';

    /**
    * The database primary key value.
    *
    * @var string
    */
    // protected $primaryKey = 'id';
    // protected $keyType = 'string';
    // public $incrementing = false;


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'organization_id',
                  'destination_id',
                  'function_id',
                  'name',
                  'extension_type',
                  'code',
                  'status',
                  'forwarding_number',
                  'dynamic_queue',
                  'do_not_disturb',
                  'forwarding',
              ];


    public static function generateUniqueCode()
    {

        $orid = auth()->user()->organization_id;

        $last_item = self::where('organization_id', $orid)->where("extension_type",1)->orderBy('code','desc')->first();
        if ($last_item) {

            $lastCode = $last_item->code;
            $new_code = $lastCode + 1;


            while (self::where('organization_id', $orid)->where('code', $new_code)->exists()) {
                $new_code++;
            }

            return $new_code;
        }


        return 1000;
    }


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

    // public function setDynamicQueueAttribute($value)
    // {
    //     return implode(',', $value);
    // }

   /*  public function getDynamicQueueAttribute($value)
    {
        return explode(',', $value);
    }


    // public function setStaticQueueAttribute($value)
    // {
    //     return implode(',', $value);
    // }

    public function getStaticQueueAttribute($value)
    {
        return explode(',', $value);
    } */

    
    /**
     * Get the organization for this model.
     *
     * @return App\Models\Organization
     */
    public function organization()
    {
        return $this->belongsTo('App\Models\Organization','organization_id');
    }

    /**
     * Get the sipUser for this model.
     *
     * @return App\Models\SipUser
     */
    public function sipUser()
    {
        return $this->belongsTo('App\Models\SipUser','destination_id');
    }
    /**
     * Get the func for this model.
     *
     * @return App\Models\SipUser
     */
    public function func()
    {
        return $this->belongsTo('App\Models\Func','function_id');
    }


    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getCreatedAtAttribute($value)
    {
        return \DateTime::createFromFormat('j/n/Y g:i A', $value);
    }

    /**
     * Get updated_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getUpdatedAtAttribute($value)
    {
        return \DateTime::createFromFormat('j/n/Y g:i A', $value);
    }

}
