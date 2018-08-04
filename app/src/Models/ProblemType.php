<?php

namespace App\src\Models;


use Illuminate\Database\Eloquent\Model;

class ProblemType extends Model
{
    protected $table = 'problem_types';

    protected $fillable = [
        'name', 'description'
    ];
}