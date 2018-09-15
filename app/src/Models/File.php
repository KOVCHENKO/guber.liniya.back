<?php

namespace App\src\Models;


use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';

    protected $fillable = [
        'path', 'claim_id'
    ];
}