<?php

namespace App\src\Models;


use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $table = 'organizations';

    protected $fillable = [
        'name', 'description', 'pid'
    ];

    public function problems()
    {
        return $this->belongsToMany(Problem::class,
            'problems_organizations');
    }

    public function claims()
    {
        return $this->belongsToMany(Claim::class, 'claims_organizations', 'organization_id', 'claim_id');
    }
}