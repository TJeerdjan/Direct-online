<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects_DO';
    protected $fillable = ['client_id', 'titel', 'slug', 'beschrijving', 'categorie', 'afbeelding', 'url', 'volgorde', 'is_actief'];
    protected $casts = ['is_actief' => 'boolean'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
