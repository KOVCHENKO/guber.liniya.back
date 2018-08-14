<?php

namespace App\src\Models;


use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $table = 'claims';

    protected $fillable = [
        'name', 'description', 'firstname', 'middlename', 'lastname', 'phone', 'email', 'address_id'
    ];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class,
            'claims_organizations');
    }
}