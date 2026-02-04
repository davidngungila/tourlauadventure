<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\ImageService;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'image_id',
        'image_url',
        'icon',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get the image from gallery
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Gallery::class, 'image_id');
    }

    /**
     * Get image URL for display (from gallery or direct URL)
     */
    public function getDisplayImageUrlAttribute()
    {
        if ($this->image_id && $this->image) {
            return $this->image->display_url;
        }
        $rawUrl = $this->getAttributes()['image_url'] ?? null;
        if ($rawUrl) {
            if (str_starts_with($rawUrl, 'http://') || str_starts_with($rawUrl, 'https://')) {
                return $rawUrl;
            }
            $imageService = new ImageService();
            try {
                return $imageService->getUrl($rawUrl);
            } catch (\Exception $e) {
                return asset($rawUrl);
            }
        }
        return null;
    }

    /**
     * Scope for active activities
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for homepage display (active and ordered)
     */
    public function scopeForHomepage($query)
    {
        return $query->where('is_active', true)
                    ->orderBy('display_order', 'asc')
                    ->orderBy('name', 'asc');
    }
}












