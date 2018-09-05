<?php

namespace App\src\Models;


use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    protected $table = 'calls';

    protected $fillable = [
        'call_id', 'phone', 'link', 'ats_status', 'type', 'ext', 'processing_status'
    ];
}