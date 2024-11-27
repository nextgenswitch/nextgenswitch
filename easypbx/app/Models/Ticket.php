<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TicketFollowUp;

class Ticket extends Model
{
    
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'organization_id',
                  'ticket_id',
                  'user_id',
                  'name',
                  'phone',
                  'subject',
                  'description',
                  'record',
                  'status'
              ];



    public function followUps(){
        return $this->hasMany(TicketFollowUp::class, 'ticket_id');
    }

    /**
     * Get the user for this model.
     *
     * @return App\Models\User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }


}
