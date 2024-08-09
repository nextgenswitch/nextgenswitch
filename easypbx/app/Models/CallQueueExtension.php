<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CallQueueExtension extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'call_queue_extensions';

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
                  'allow_diversion',
                  'call_queue_id',
                  'extension_id',
                  'member_type',
                  'priority'
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
     * Get the callQueue for this model.
     *
     * @return App\Models\CallQueue
     */
    public function callQueue()
    {
        return $this->belongsTo('App\Models\CallQueue','call_queue_id');
    }

    /**
     * Get the extension for this model.
     *
     * @return App\Models\Extension
     */
    public function extension()
    {
        return $this->belongsTo('App\Models\Extension','extension_id');
    }

    


}
