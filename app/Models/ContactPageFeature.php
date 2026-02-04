<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPageFeature extends Model
{
    protected $fillable = [
        'title',
        'description',
        'icon',
        'image_url',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];
}
