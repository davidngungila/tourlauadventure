<?php

namespace App\Services;

use App\Models\Tour;
use App\Models\Destination;
use Illuminate\Support\Collection;

class PublicDataService
{
    /**
     * Get tours for a specific category or search term.
     */
    public function getFeaturedTours(int $limit = 6): Collection
    {
        return Tour::with('destination')
            ->where('status', 'active')
            ->where('publish_status', 'published')
            ->orderBy('is_featured', 'desc')
            ->orderBy('price', 'asc')
            ->limit($limit)
            ->get()
            ->map(fn($tour) => $this->formatTourData($tour));
    }

    /**
     * Get safari specific tours.
     */
    public function getSafariTours(): Collection
    {
        return Tour::with('destination')
            ->where('status', 'active')
            ->where('publish_status', 'published')
            ->where(function($query) {
                $query->where('name', 'like', '%Safari%')
                      ->orWhere('description', 'like', '%Safari%')
                      ->orWhere('short_description', 'like', '%Safari%');
            })
            ->orderBy('is_featured', 'desc')
            ->orderBy('price', 'asc')
            ->get()
            ->map(fn($tour) => $this->formatTourData($tour));
    }

    /**
     * Standardize tour data for frontend.
     */
    public function formatTourData(Tour $tour): array
    {
        return [
            'id' => $tour->id,
            'name' => $tour->name,
            'slug' => $tour->slug,
            'price' => (float) $tour->price,
            'starting_price' => (float) ($tour->starting_price ?? $tour->price),
            'duration_days' => $tour->duration_days,
            'image' => $tour->image_url ? (str_starts_with($tour->image_url, 'http') ? $tour->image_url : asset($tour->image_url)) : asset('images/hero-slider/safari-adventure.jpg'),
            'description' => $tour->short_description ?: substr($tour->description ?? '', 0, 200) . '...',
            'destination' => $tour->destination ? $tour->destination->name : 'Tanzania',
            'rating' => $tour->rating ?? 4.5,
            'is_featured' => $tour->is_featured ?? false,
        ];
    }

    /**
     * Get all destinations.
     */
    public function getAllDestinations(): Collection
    {
        return Destination::where('status', 'active')->get();
    }
}
