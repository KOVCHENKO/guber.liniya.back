<?php

namespace App\src\Models;


use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $table = 'claims';

    protected $fillable = [
        'name', 'description',
        'firstname', 'middlename', 'lastname', 'phone', 'email', 'address_id',
        'link', 'ats_status', 'problem_id', 'status', 'dispatch_status', 'pid', 'close_status'
    ];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class,
            'claims_organizations');
    }

    public function problem()
    {
        return $this->belongsTo(Problem::class, 'problem_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}