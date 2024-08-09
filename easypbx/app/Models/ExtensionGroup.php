<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtensionGroup extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'extension_groups';

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
                  'organization_id',
                  'name',
                  'extension_id',
                  'algorithm'
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

    public function getExtensionIdAttribute($value)
    {
        return explode(',', $value);
    }

    public function setExtensionIdAttribute($value)
    {
        $this->attributes['extension_id'] = !empty($value) ? implode(',',$value) : null;
    }

    
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
     * Get the extension for this model.
     *
     * @return App\Models\Extension
     */
    public function extension()
    {
        return $this->belongsTo('App\Models\Extension','extension_id');
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
