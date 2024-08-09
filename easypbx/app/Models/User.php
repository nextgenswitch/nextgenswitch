<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
                  'organization_id',
                  'name',
                  'email',
                  'password',
                  'role',
                  'status',
                  'email_verified_at',
                  'remember_token'
              ];

    
    /**
     * The channels the user receives notification broadcasts on.
     */
    public function receivesBroadcastNotificationsOn(): string
    {
        return 'users.'.$this->id;
    }
    
    public function canSendCall(){
        return true;
    }
     /**
     * Get the plan for this model.
     *
     * @return App\Models\Plan
     */
    public function plan()
    {
        return $this->belongsTo('App\Models\Plan','plan_id');
    }

    /**
     * Get the paymentMethod for this model.
     *
     * @return App\Models\PaymentMethod
     */
    public function organization()
    {
        return $this->belongsTo('App\Models\Organization','organization_id');
    }



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getIsSuperAdminAttribute()
    {
        return ($this->role == 'admin') ? true : false;
    }

    public function getIsUserAttribute()
    {
        return ($this->role == 'user') ? true : false;
    }

}
