<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

}
