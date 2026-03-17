<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class ClientUser extends Authenticatable
{
    protected $table = 'users_DO';
    protected $fillable = ['client_id', 'naam', 'email', 'password', 'role', 'taal'];
    protected $hidden = ['password'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
