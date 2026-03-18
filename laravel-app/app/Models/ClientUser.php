<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class ClientUser extends Authenticatable
{
    protected $table = 'users_DO';
    protected $fillable = ['client_id', 'email', 'naam', 'password_hash', 'role', 'is_active'];
    protected $hidden = ['password_hash'];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
