<?php

namespace App\src\Models;


use Illuminate\Database\Eloquent\Model;

class ProblemType extends Model
{
    protected $table = 'problem_types';

    protected $fillable = [
        'name', 'description'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * У типа проблем - много проблем
     */
    public function problems()
    {
        return $this->hasMany(Problem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * То же, что и выше, только problems - в качестве потомков
     */
    public function children()
    {
        return $this->hasMany(Problem::class);
    }
}