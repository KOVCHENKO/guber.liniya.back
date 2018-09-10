<?php

namespace App\src\Models;


use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'text', 'claim_id', 'status'
    ];

}