<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'auth_users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'username',
        'google_id',
        'passwordHash',
        'role',
        'avatar',
    ];
    protected $hidden = [
        'passwordHash',
        'google_id'
    ];

    public function getAuthPassword()
    {
        return $this->passwordHash;
    }

    public function player()
    {
        return $this->hasOne(Player::class, 'user_id', 'id');
    }
}