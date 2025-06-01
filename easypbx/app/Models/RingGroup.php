<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RingGroup extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ring_groups';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'organization_id',
                  'extension_id',
                  'function_id',
                  'destination_id',
                  'description',
                  'ring_strategy',
                  'ring_time',
                  'answer_channel',
                  'skip_busy_extension',
                  'allow_diversions',
                  'ringback_tone',
                  'extensions'
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
    
    /**
     * Get the extensionGroup for this model.
     *
     * @return App\Models\ExtensionGroup
     */
    public function extensionGroup()
    {
        return $this->belongsTo('App\Models\ExtensionGroup','extension_group_id');
    }

    public function extension()
    {
        return $this->belongsTo('App\Models\Extension','extension_id');
    }

    public function func(){
        return $this->belongsTo('App\Models\Func', 'function_id');
    }

}
