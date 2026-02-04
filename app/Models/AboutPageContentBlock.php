<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\ImageService;

class AboutPageContentBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'block_type',
        'title',
        'subtitle',
        'content',
        'description',
        'image_id',
        'image_url',
        'images',
        'data',
        'icon',
        'button_text',
        'button_link',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'data' => 'array',
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
}
