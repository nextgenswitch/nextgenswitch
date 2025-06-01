<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedIp extends Model
{
    use HasFactory;
    protected $table = 'failed_ips';
    protected $fillable = [
        'organization_id',
        'ip',
        'username',
    ];
}
