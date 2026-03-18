<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = 'testimonials';
    protected $fillable = ['client_id', 'client_name', 'client_title', 'client_company', 'photo_id', 'quote', 'rating', 'sort_order', 'is_visible'];
    protected $casts = ['is_visible' => 'boolean'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
