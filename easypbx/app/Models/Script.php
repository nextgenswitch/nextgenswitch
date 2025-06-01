<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Script extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'scripts';

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
                  'content',
                  'name',
                  'organization_id'
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
     * Get the organization for this model.
     *
     * @return App\Models\Organization
     */
    public function organization()
    {
        return $this->belongsTo('App\Models\Organization','organization_id');
    }

    public static function prepare($content, $contact){
        
        $keywords = [
            '%first_name%' => 'first_name', 
            '%last_name%' => 'last_name',
            '%email%'  => 'email',
            '%phone%' => 'tel_no',
            '%address%' => 'address',
            '%city%' => 'city',
            '%state%' => 'state',
            '%post_code%' => 'post_code',
            '%country%' => 'country'
        ];


        foreach($keywords as $key => $keyword){
            $content = str_replace($key, $contact->{$keyword}, $content);
        }

        return $content;
    }

}
