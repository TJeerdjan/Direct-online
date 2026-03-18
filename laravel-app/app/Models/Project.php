<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects_DO';
    protected $fillable = ['client_id', 'title', 'slug', 'description', 'image_id', 'external_url', 'category', 'sort_order', 'is_visible'];
    protected $casts = ['is_visible' => 'boolean'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
