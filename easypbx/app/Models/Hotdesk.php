<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotdesk extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hotdesks';

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
                  'name',
                  'organization_id',
                  'sip_user_id'
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
     * Get the sipUser for this model.
     *
     * @return App\Models\SipUser
     */
    public function sipUser()
    {
        return $this->belongsTo('App\Models\SipUser','sip_user_id');
    }


}
