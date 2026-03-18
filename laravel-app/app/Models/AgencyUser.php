<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class AgencyUser extends Authenticatable
{
    protected $table = 'agency_users';
    protected $fillable = ['name', 'email', 'password_hash', 'role', 'is_active'];
    protected $hidden = ['password_hash'];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
