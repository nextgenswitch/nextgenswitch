<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'ws_url',
        'prompt',
        'extra_parameters',
        'max_call_duration',
        'record',
        'forwarding_number',
        'email',
        'function_id',
        'destination_id',
        'greetings',
    ];

    public function histories()
    {
        return $this->hasMany(StreamHistory::class);
    }


    public function function()
    {
        return $this->belongsTo('App\Models\Func','function_id');
    }
}
