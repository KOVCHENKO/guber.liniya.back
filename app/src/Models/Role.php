<?php

namespace App\src\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed name - наименование роли
 */
class Role extends Model
{
    protected $fillable = [
        'name', 'email', 'password',
    ];
}