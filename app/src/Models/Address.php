<?php

namespace App\src\Models;


use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';

    protected $fillable = [
        'district', 'city', 'street', 'building'
    ];
}
