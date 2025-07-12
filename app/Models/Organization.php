<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_id',
        'name',
        'domain',
        'contact_no',
        'email',
        'address',
        'credit',
        'call_limit',
        'max_extension',
        'expire_date',
        'is_default',
        'is_primary',
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
     * Get the plan for this model.
     *
     * @return App\Models\Plan
     */
    public function plan()
    {
        return $this->belongsTo('App\Models\Plan', 'plan_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
