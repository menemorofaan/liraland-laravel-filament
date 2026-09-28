<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- НАШИ НОВЫЕ МЕТОДЫ ДЛЯ ЛИЦЕНЗИЙ ---

    public function licenses()
    {
        return $this->hasMany(License::class);
    }

    // Проверка: есть ли у пользователя активная лицензия (подписка или USB-донгл)
    public function hasActiveLicense(): bool
    {
        return $this->licenses()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->where('license_model', 'perpetual')
                      ->orWhere(function ($sub) {
                          $sub->where('license_model', 'subscription')
                              ->where('expires_at', '>=', now()->toDateString());
                      });
            })
            ->exists();
    }
}