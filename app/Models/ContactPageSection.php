<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPageSection extends Model
{
    protected $fillable = [
        'section_key',
        'section_name',
        'content',
        'data',
        'image_url',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'data' => 'array',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];
}
