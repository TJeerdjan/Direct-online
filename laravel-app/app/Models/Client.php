<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'klanten_DO';
    protected $fillable = ['naam', 'slug', 'domain', 'plan', 'status', 'contact_naam', 'contact_email', 'api_key', 'modules'];
    protected $casts = ['modules' => 'array'];

    public function users()
    {
        return $this->hasMany(ClientUser::class, 'client_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class, 'client_id');
    }

    public function formSubmissions()
    {
        return $this->hasMany(FormSubmission::class, 'client_id');
    }

    public function settings()
    {
        return $this->hasOne(ClientSetting::class, 'client_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'client_id');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'client_id');
    }
}
