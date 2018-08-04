<?php

namespace App\src\Models;


use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $table = 'organizations';

    protected $fillable = [
        'name', 'description', 'pid'
    ];
}