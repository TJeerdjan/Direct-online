<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientSetting extends Model
{
    protected $table = 'client_settings';
    public $timestamps = false;
    const UPDATED_AT = 'updated_at';
    protected $fillable = ['client_id', 'site_name', 'tagline', 'logo_id', 'primary_color', 'accent_color', 'social_instagram', 'social_linkedin', 'social_facebook', 'social_twitter'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
