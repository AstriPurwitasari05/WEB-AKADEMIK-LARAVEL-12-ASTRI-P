<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['username', 'password'];

    protected $hidden = ['password', 'remember_token'];

    public function getAuthIdentifierName()
    {
        return 'username'; // agar login pakai username, bukan email
    }
}