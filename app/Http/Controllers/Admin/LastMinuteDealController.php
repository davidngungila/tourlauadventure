<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tour;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LastMinuteDealController extends BaseAdminController
{
    /**
     * Display a listing of last-minute deals
     */
    public function index(Request $request)
    {
        $query = Tour::with(['destination'])
            ->where('is_last_minute_deal', true);
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tour_code', 'like', "%{$search}%");
            });
        }
        
        // Destination filter
        if ($request->filled('destination_id')) {
            $query->where('destination_id', $request->destination_id);
        }
        
        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('last_minute_deal_expires_at', '>', now())
                      ->where('status', 'active')
                      ->where('publish_status', 'published');
            } elseif ($request->status === 'expired') {
                $query->where(function($q) {
                    $q->where('last_minute_deal_expires_at', '<', now())
                      ->orWhereNull('last_minute_deal_expires_at');
                });
            }
        }
        
        $tours = $query->orderBy('last_minute_deal_expires_at', 'asc')
                       ->orderBy('created_at', 'desc')
                       ->paginate(20);
        
        $destinations = Destination::orderBy('name')->get();
        
        // Statistics
        $stats = [
            'total' => Tour::where('is_last_minute_deal', true)->count(),
            'active' => Tour::where('is_last_minute_deal', true)
                           ->where('last_minute_deal_expires_at', '>', now())
                           ->where('status', 'active')
                           ->where('publish_status', 'published')
                           ->count(),
            'expired' => Tour::where('is_last_minute_deal', true)
                            ->where(function($q) {
                                $q->where('last_minute_deal_expires_at', '<', now())
                                  ->orWhereNull('last_minute_deal_expires_at');
                            })
                            ->count(),
            'avg_discount' => Tour::where('is_last_minute_deal', true)
                                 ->whereNotNull('last_minute_discount_percentage')
                                 ->avg('last_minute_discount_percentage'),
        ];
        
        return view('admin.tours.last-minute-deals', compact('tours', 'destinations', 'stats'));
    }
    
    /**
     * Show the form for creating/editing a last-minute deal
     */
    public function create()
    {
        $tours = Tour::where('is_last_minute_deal', false)
                    ->where('status', 'active')
                    ->where('publish_status', 'published')
                    ->orderBy('name')
                    ->get();
        
        return view('admin.tours.last-minute-deal-form', compact('tours'));
    }
    
    /**
     * Store a new last-minute deal
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'expires_at' => 'required|date|after:now',
        ]);
        
        $tour = Tour::findOrFail($validated['tour_id']);
        
        // Store original price if not already stored
        if (!$tour->last_minute_original_price) {
            $tour->last_minute_original_price = $tour->starting_price ?? $tour->price;
        }
        
        // Calculate discounted price
        $originalPrice = $tour->last_minute_original_price ?? $tour->starting_price ?? $tour->price;
        $discountAmount = ($originalPrice * $validated['discount_percentage']) / 100;
        $discountedPrice = $originalPrice - $discountAmount;
        
        // Update tour with last-minute deal info
        $tour->update([
            'is_last_minute_deal' => true,
            'last_minute_discount_percentage' => $validated['discount_percentage'],
            'last_minute_deal_expires_at' => $validated['expires_at'],
            'starting_price' => $discountedPrice,
        ]);
        
        return $this->successResponse(
            'Last-minute deal created successfully!',
            route('admin.tours.last-minute-deals')
        );
    }
    
    /**
     * Show the form for editing a last-minute deal
     */
    public function edit($id)
    {
        $tour = Tour::where('is_last_minute_deal', true)
                   ->findOrFail($id);
        
        return view('admin.tours.last-minute-deal-form', compact('tour'));
    }
    
    /**
     * Update a last-minute deal
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'expires_at' => 'required|date|after:now',
        ]);
        
        $tour = Tour::where('is_last_minute_deal', true)
                   ->findOrFail($id);
        
        // Ensure original price is stored
        if (!$tour->last_minute_original_price) {
            $tour->last_minute_original_price = $tour->starting_price ?? $tour->price;
        }
        
        // Calculate discounted price
        $originalPrice = $tour->last_minute_original_price;
        $discountAmount = ($originalPrice * $validated['discount_percentage']) / 100;
        $discountedPrice = $originalPrice - $discountAmount;
        
        $tour->update([
            'last_minute_discount_percentage' => $validated['discount_percentage'],
            'last_minute_deal_expires_at' => $validated['expires_at'],
            'starting_price' => $discountedPrice,
        ]);
        
        return $this->successResponse(
            'Last-minute deal updated successfully!',
            route('admin.tours.last-minute-deals')
        );
    }
    
    /**
     * Remove a tour from last-minute deals
     */
    public function destroy($id)
    {
        $tour = Tour::where('is_last_minute_deal', true)
                   ->findOrFail($id);
        
        // Restore original price
        if ($tour->last_minute_original_price) {
            $tour->starting_price = $tour->last_minute_original_price;
        }
        
        $tour->update([
            'is_last_minute_deal' => false,
            'last_minute_discount_percentage' => null,
            'last_minute_deal_expires_at' => null,
            'last_minute_original_price' => null,
        ]);
        
        return $this->successResponse(
            'Tour removed from last-minute deals successfully!',
            route('admin.tours.last-minute-deals')
        );
    }
    
    /**
     * Bulk actions for last-minute deals
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:remove,extend',
            'tour_ids' => 'required|array',
            'tour_ids.*' => 'exists:tours,id',
        ]);
        
        $tourIds = $request->tour_ids;
        $action = $request->action;
        $count = 0;
        
        switch ($action) {
            case 'remove':
                $tours = Tour::whereIn('id', $tourIds)
                            ->where('is_last_minute_deal', true)
                            ->get();
                
                foreach ($tours as $tour) {
                    if ($tour->last_minute_original_price) {
                        $tour->starting_price = $tour->last_minute_original_price;
                    }
                    
                    $tour->update([
                        'is_last_minute_deal' => false,
                        'last_minute_discount_percentage' => null,
                        'last_minute_deal_expires_at' => null,
                        'last_minute_original_price' => null,
                    ]);
                }
                
                $count = $tours->count();
                $message = "{$count} tour(s) removed from last-minute deals successfully!";
                break;
                
            case 'extend':
                $request->validate([
                    'extend_days' => 'required|integer|min:1|max:365',
                ]);
                
                $tours = Tour::whereIn('id', $tourIds)
                            ->where('is_last_minute_deal', true)
                            ->get();
                
                foreach ($tours as $tour) {
                    $currentExpiry = $tour->last_minute_deal_expires_at ?? now();
                    $newExpiry = Carbon::parse($currentExpiry)->addDays($request->extend_days);
                    
                    $tour->update([
                        'last_minute_deal_expires_at' => $newExpiry,
                    ]);
                }
                
                $count = $tours->count();
                $message = "{$count} tour(s) extended by {$request->extend_days} day(s) successfully!";
                break;
        }
        
        return $this->successResponse($message, route('admin.tours.last-minute-deals'));
    }
    
    /**
     * Quick add tour to last-minute deals
     */
    public function quickAdd(Request $request)
    {
        $validated = $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'days_valid' => 'required|integer|min:1|max:365',
        ]);
        
        $tour = Tour::findOrFail($validated['tour_id']);
        
        if ($tour->is_last_minute_deal) {
            return $this->errorResponse(
                'This tour is already a last-minute deal!',
                route('admin.tours.last-minute-deals')
            );
        }
        
        // Store original price
        $tour->last_minute_original_price = $tour->starting_price ?? $tour->price;
        
        // Calculate discounted price
        $originalPrice = $tour->last_minute_original_price;
        $discountAmount = ($originalPrice * $validated['discount_percentage']) / 100;
        $discountedPrice = $originalPrice - $discountAmount;
        
        // Set expiry date
        $expiresAt = now()->addDays($validated['days_valid']);
        
        $tour->update([
            'is_last_minute_deal' => true,
            'last_minute_discount_percentage' => $validated['discount_percentage'],
            'last_minute_deal_expires_at' => $expiresAt,
            'starting_price' => $discountedPrice,
        ]);
        
        return $this->successResponse(
            'Tour added to last-minute deals successfully!',
            route('admin.tours.last-minute-deals')
        );
    }
}
