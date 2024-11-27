<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;
class Contact extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contacts';

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
        'organization_id',
        'first_name',
        'last_name',
        'gender',
        'email',
        'cc',
        'tel_no',
        'contact_groups',
        'address',
        'city',
        'state',
        'post_code',
        'country',
        'notes'
    ];

    public function setFirstNameAttribute( $value ) {
        $this->attributes['first_name'] =  $value ? $value : 'Unnamed';
    }

    protected function telNo(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => self::sanitize_phone($value),
            set: fn (string $value) => self::sanitize_phone($value),
        );
    }

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

    public function getContactGroupsAttribute( $value ) {
        return explode( ',', $value );
    }

    public function setContactGroupsAttribute( $value ) {
        $this->attributes['contact_groups'] =  ! empty( $value ) ? implode( ',', $value ) : null;
    }

    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getCreatedAtAttribute( $value ) {
        return \DateTime::createFromFormat( 'j/n/Y g:i A', $value );
    }

    /**
     * Get updated_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getUpdatedAtAttribute( $value ) {
        return \DateTime::createFromFormat( 'j/n/Y g:i A', $value );
    }

    public static function  sanitize_phone( $phone ) {

        $intl = false;
        if(strlen($phone) == 0)
           return $phone;
        $phone = trim($phone); 
        if(substr($phone, 0, 1) == '+'){
          $intl = true;
          $phone = substr($phone, 1);
        }
      
        $phone = preg_replace('/\D+/', '', $phone);
      
        if($intl) $phone = '+' . $phone;
      
        return $phone;
      
    }

    //Contact::getContacts([3,4])->count()
    public static function  getContacts( $groups ): array | object{
        //$contacts = Contact::select( [DB::raw( "CONCAT(COALESCE(cc, ''),tel_no) as tel" )] );
        $contacts = Contact::query();

        foreach ( $groups as $key => $groupId ) {
            $statement = $key === 0 ? 'whereRaw' : 'orWhereRaw';
            $contacts->{$statement}

            ( 'FIND_IN_SET(?, contact_groups)', [$groupId] );
        }

        return  $contacts
            ->groupBy( 'tel_no' )
            ->pluck( 'tel_no' ,'id');
        
    }

}
