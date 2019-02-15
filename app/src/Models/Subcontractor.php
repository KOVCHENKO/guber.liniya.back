<?php

namespace App\src\Models;


use Illuminate\Database\Eloquent\Model;

class Subcontractor extends Model
{

    protected $fillable = [
        'claim_id', 'organization_id', 'description', 'status'
    ];

    public function claim()
    {
        return $this->hasOne(Claim::class, 'id', 'claim_id');
    }

}
