<?php

namespace App\src\Models;


use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $table = 'claims';

    protected $fillable = [
        'name', 'description',
        'firstname', 'middlename', 'lastname', 'phone', 'email', 'address_id',
        'link', 'ats_status', 'problem_id', 'status', 'dispatch_status', 'pid', 'close_status'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * Организации привязанные к заявке
     */
    public function organizations()
    {
        return $this->belongsToMany(Organization::class,
            'claims_organizations');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * Проблема, привязанная к заявке
     */
    public function problem()
    {
        return $this->belongsTo(Problem::class, 'problem_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * Адрес, привязанный к заявке
     */
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * Комментарии, привязанные к заявке
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * К заявке могут быть привязаны различные файлы
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }
}