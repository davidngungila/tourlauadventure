<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'featured_image',
        'images',
        'event_date',
        'event_time',
        'end_date',
        'end_time',
        'location',
        'address',
        'venue',
        'latitude',
        'longitude',
        'organizer',
        'contact_email',
        'contact_phone',
        'ticket_url',
        'ticket_price',
        'max_attendees',
        'registered_attendees',
        'event_type',
        'status',
        'is_featured',
        'meta_title',
        'meta_description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'images' => 'array',
        'event_date' => 'date',
        'end_date' => 'date',
        'ticket_price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'max_attendees' => 'integer',
        'registered_attendees' => 'integer',
        'is_featured' => 'boolean',
    ];
}






