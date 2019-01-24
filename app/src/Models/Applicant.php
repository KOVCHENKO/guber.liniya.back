<?php

namespace App\src\Models;


use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $table = 'applicants';

    protected $fillable = [
        'id', 'firstname', 'lastname', 'middlename', 'phone', 'email', 'address_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * Адрес, привязанный к заявителю
     */
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }
}
