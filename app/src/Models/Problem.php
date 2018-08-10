<?php

namespace App\src\Models;


use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    protected $table = 'problems';

    protected $fillable = [
        'name', 'description', 'problem_type_id'
    ];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'problems_organizations');
    }

}