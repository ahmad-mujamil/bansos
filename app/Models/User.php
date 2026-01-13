<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RoleUser;
use App\Enums\StatusUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;
    protected $keyType = 'string';
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'role' => RoleUser::class,
            'status' => StatusUser::class,
        ];
    }

    public function is_super() : bool
    {
        return $this->role->value==='super';
    }

    public function is_admin() : bool
    {
        return $this->role->value==='admin';
    }

    public function is_user() : bool
    {
        return $this->role->value==='user';
    }

}
