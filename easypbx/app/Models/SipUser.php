<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SipUser extends Model
{
    use HasFactory;

    protected $fillable = ['organization_id', 'username', 'password', 'host', 'port', 'transport', 'peer', 'record','status', 'call_limit'];
    /**
     * Get the organization for this model.
     *
     * @return App\Models\Organization
     */
    public function extension()
    {
        return $this->hasOne('App\Models\Extension','destination_id');
    }

    public function trunk()
    {
        return $this->hasOne('App\Models\Trunk','sip_user_id');
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
}
