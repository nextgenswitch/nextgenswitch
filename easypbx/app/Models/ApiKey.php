<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'api_keys';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    // public $incrementing = false;


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'organization_id',
                  'title',
                  'key',
                  'secret',
                  'status'
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
     * Generate a secure unique API key
     *
     * @return string
     */
    public static function generateKey()
    {
        do {
            $key = Str::random(64);
        } while (self::keyExists($key));

        return $key;
    }

    public static function keyExists($key)
    {
        return self::where('key', $key)->first() instanceof self;
    }

    /**
     * Generate a key/secret pair
     *
     * @return array
     */
    public static function generateSecret() {
        return Str::random(64);
    }

    public static function getByKey($key)
    {
        return self::where([
            'key'    => $key,
            'status' => 1
        ])->first();
    }

 

}
