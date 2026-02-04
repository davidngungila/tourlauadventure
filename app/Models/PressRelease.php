<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PressRelease extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'images',
        'author',
        'release_date',
        'category',
        'tags',
        'meta_title',
        'meta_description',
        'status',
        'views',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'images' => 'array',
        'tags' => 'array',
        'release_date' => 'date',
        'views' => 'integer',
    ];
}






