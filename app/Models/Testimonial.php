<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $table = 'testimonials';

    protected $fillable = [
        'author_name',
        'author_title',
        'author_image_url',
        'content',
        'rating',
        'tour_id',
        'is_approved',
        'is_featured',
        'is_verified',
        'display_order',
        'source',
        'review_url',
        'review_date',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'display_order' => 'integer',
        'review_date' => 'date',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}

