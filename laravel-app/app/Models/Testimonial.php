<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = 'testimonials';
    protected $fillable = ['client_id', 'naam', 'bedrijf', 'tekst', 'score', 'is_actief', 'volgorde'];
    protected $casts = ['is_actief' => 'boolean'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
