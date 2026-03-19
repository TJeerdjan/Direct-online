<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media_DO';
    public $timestamps = false;
    protected $fillable = ['client_id', 'file_name', 'file_path', 'alt_text', 'media_type', 'mime_type', 'file_size', 'usage_key', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function getUrlAttribute(): string
    {
        return '/uploads/' . $this->file_path;
    }
}
