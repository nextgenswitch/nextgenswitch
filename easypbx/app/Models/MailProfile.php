<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'default',
        'name',
        'options',
        'organization_id',
        'provider',
        'status'
    ];

    public function organization()
    {
        return $this->belongsTo('App\Models\Organization','organization_id');
    }
}
