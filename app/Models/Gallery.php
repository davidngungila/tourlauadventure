<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\ImageService;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'galleries';

    protected $fillable = [
        'title',
        'description',
        'caption',
        'alt_text',
        'image_url',
        'original_filename',
        'file_size',
        'mime_type',
        'width',
        'height',
        'thumbnail_150',
        'thumbnail_300',
        'thumbnail_600',
        'thumbnail_hd',
        'webp_url',
        'category',
        'album_id',
        'tags',
        'display_order',
        'priority',
        'visibility',
        'visible_from',
        'visible_until',
        'click_action',
        'click_link',
        'seo_filename',
        'seo_alt_text',
        'auto_optimize',
        'convert_to_webp',
        'resize_large',
        'optimization_quality',
        'uploaded_by',
        'uploaded_at',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'display_order' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'file_size' => 'integer',
        'auto_optimize' => 'boolean',
        'convert_to_webp' => 'boolean',
        'resize_large' => 'boolean',
        'optimization_quality' => 'integer',
        'visible_from' => 'datetime',
        'visible_until' => 'datetime',
        'uploaded_at' => 'datetime',
    ];

    /**
     * Get the album that the gallery item belongs to.
     */
    public function album()
    {
        return $this->belongsTo(GalleryAlbum::class, 'album_id');
    }

    /**
     * Get the user who uploaded the image.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get tags relationship (many-to-many)
     */
    public function tagRelations()
    {
        return $this->belongsToMany(GalleryTag::class, 'gallery_gallery_tag', 'gallery_id', 'gallery_tag_id');
    }

    /**
     * Get the image URL for display (handles both storage paths and external URLs)
     */
    public function getDisplayUrlAttribute()
    {
        if (!$this->attributes['image_url'] ?? null) {
            return null;
        }
        
        $imageService = new ImageService();
        return $imageService->getUrl($this->attributes['image_url']);
    }

    /**
     * Get internal URL format (relative path like /images/safari_home-1.jpg)
     */
    public function getInternalUrlAttribute()
    {
        if (!$this->attributes['image_url'] ?? null) {
            return null;
        }

        $url = $this->attributes['image_url'];
        
        // If it's already a full URL (external), return null (not internal)
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://') || str_starts_with($url, 'data:')) {
            return null; // External URLs don't have internal format
        }
        
        // If it starts with /storage/, convert to /images/ format
        if (str_starts_with($url, '/storage/') || str_starts_with($url, 'storage/')) {
            // Extract filename from storage path
            $filename = basename($url);
            return '/images/' . $filename;
        }
        
        // If it already starts with /images/, return as is
        if (str_starts_with($url, '/images/') || str_starts_with($url, 'images/')) {
            return str_starts_with($url, '/') ? $url : '/' . $url;
        }
        
        // For other paths, try to extract filename and use /images/ format
        $filename = basename($url);
        if ($filename && $filename !== $url) {
            return '/images/' . $filename;
        }
        
        // Fallback: return as relative path
        return str_starts_with($url, '/') ? $url : '/' . $url;
    }

    /**
     * Get external URL format (full URL like http://127.0.0.1:8003/images/Mara-River-3-1536x1024.jpg)
     */
    public function getExternalUrlAttribute()
    {
        if (!$this->attributes['image_url'] ?? null) {
            return null;
        }

        $url = $this->attributes['image_url'];
        
        // If it's already a full URL, return as is
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }
        
        // If it's a data URL, return as is
        if (str_starts_with($url, 'data:')) {
            return $url;
        }
        
        // Convert to full URL
        $baseUrl = rtrim(config('app.url', url('/')), '/');
        
        // Get internal URL first
        $internalUrl = $this->internal_url;
        if ($internalUrl) {
            return $baseUrl . $internalUrl;
        }
        
        // For storage paths, convert to /images/ format
        if (str_starts_with($url, '/storage/') || str_starts_with($url, 'storage/')) {
            $filename = basename($url);
            return $baseUrl . '/images/' . $filename;
        }
        
        // For other relative paths, ensure they start with /
        $relativePath = str_starts_with($url, '/') ? $url : '/' . $url;
        return $baseUrl . $relativePath;
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrl($size = '300')
    {
        $thumbField = "thumbnail_{$size}";
        if ($this->$thumbField) {
            $imageService = new ImageService();
            return $imageService->getUrl($this->$thumbField);
        }
        
        // Fallback to main image
        return $this->display_url;
    }

    /**
     * Check if image is currently visible
     */
    public function isVisible(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        
        if ($this->visible_from && $now->lt($this->visible_from)) {
            return false;
        }

        if ($this->visible_until && $now->gt($this->visible_until)) {
            return false;
        }

        return true;
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }
}
