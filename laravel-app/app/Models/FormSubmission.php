<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    protected $table = 'formulieren_DO';
    public $timestamps = false;
    protected $fillable = ['client_id', 'naam', 'email', 'telefoon', 'bericht', 'bron_pagina', 'is_gelezen', 'is_gearchiveerd', 'status'];
    protected $casts = ['is_gelezen' => 'boolean', 'is_gearchiveerd' => 'boolean'];
}
