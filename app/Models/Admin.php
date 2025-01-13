<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    const ROLE_ADMIN = 1;
    const ROLE_STAFF = 2;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Helper method to check if the user is an Admin
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    // Helper method to check if the user is a Staff
    public function isStaff()
    {
        return $this->role === self::ROLE_STAFF;
    }
}
