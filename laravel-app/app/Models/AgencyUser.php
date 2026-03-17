<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class AgencyUser extends Authenticatable
{
    protected $table = 'agency_users';
    protected $fillable = ['naam', 'email', 'password', 'role'];
    protected $hidden = ['password'];
}
