<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaKit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'assets',
        'company_info',
        'contact_info',
        'social_links',
        'download_url',
        'status',
        'downloads',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'assets' => 'array',
        'contact_info' => 'array',
        'social_links' => 'array',
        'downloads' => 'integer',
    ];
}






