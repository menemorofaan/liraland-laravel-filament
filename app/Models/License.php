<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class License extends Model
{
    protected $guarded = [];

    // Связь: Лицензия принадлежит одному Пользователю (User)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}