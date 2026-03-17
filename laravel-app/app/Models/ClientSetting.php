<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientSetting extends Model
{
    protected $table = 'client_settings';
    protected $fillable = ['client_id', 'site_naam', 'logo', 'primaire_kleur', 'secundaire_kleur', 'over_tekst', 'facebook', 'instagram', 'linkedin', 'twitter', 'telefoon', 'email'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
