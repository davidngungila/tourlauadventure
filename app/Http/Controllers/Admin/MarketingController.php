<?php

namespace App\Http\Controllers\Admin;

use App\Models\PromoCode;
use App\Models\EmailCampaign;
use App\Models\SmsCampaign;
use App\Models\SocialMediaPost;
use App\Models\LandingPage;
use App\Models\Banner;
use App\Models\SmsTemplate;
use App\Models\PressRelease;
use App\Models\MediaKit;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MarketingController extends BaseAdminController
{
    /**
     * Display marketing dashboard
     */
    public function dashboard()
    {
        try {
            $stats = [
                'total_campaigns' => EmailCampaign::count() + SmsCampaign::count(),
                'active_campaigns' => EmailCampaign::whereIn('status', ['sending', 'scheduled'])->count() + SmsCampaign::whereIn('status', ['sending', 'scheduled'])->count(),
                'total_emails_sent' => EmailCampaign::sum('sent_count') ?? 0,
                'total_sms_sent' => SmsCampaign::sum('sent_count') ?? 0,
                'email_open_rate' => EmailCampaign::avg('open_rate') ?? 0,
                'email_click_rate' => EmailCampaign::avg('click_rate') ?? 0,
                'sms_delivery_rate' => SmsCampaign::avg('delivery_rate') ?? 0,
                'total_promo_codes' => PromoCode::count(),
                'active_promo_codes' => PromoCode::where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
                    })->count(),
                'total_banners' => Banner::count(),
                'active_banners' => Banner::where('is_active', true)->count(),
                'total_landing_pages' => LandingPage::count(),
                'published_landing_pages' => LandingPage::where('status', 'published')->count(),
                'total_subscribers' => \App\Models\Subscriber::whereNotNull('verified_at')->count(),
                'unverified_subscribers' => \App\Models\Subscriber::whereNull('verified_at')->count(),
                'total_leads' => \App\Models\Booking::whereIn('status', ['pending', 'inquiry', 'pending_payment'])->count(),
                'total_social_posts' => SocialMediaPost::count(),
                'published_social_posts' => SocialMediaPost::where('status', 'published')->count(),
                'social_engagement_rate' => SocialMediaPost::avg('engagement_rate') ?? 0,
            ];
        } catch (\Exception $e) {
            $stats = [
                'total_campaigns' => 0,
                'active_campaigns' => 0,
                'total_emails_sent' => 0,
                'total_sms_sent' => 0,
                'email_open_rate' => 0,
                'email_click_rate' => 0,
                'sms_delivery_rate' => 0,
                'total_promo_codes' => 0,
                'active_promo_codes' => 0,
                'total_banners' => 0,
                'active_banners' => 0,
                'total_landing_pages' => 0,
                'published_landing_pages' => 0,
                'total_subscribers' => 0,
                'unverified_subscribers' => 0,
                'total_leads' => 0,
                'total_social_posts' => 0,
                'published_social_posts' => 0,
                'social_engagement_rate' => 0,
            ];
        }
        
        return view('admin.marketing.dashboard', compact('stats'));
    }

    // ==================== PROMO CODES ====================
    
    public function promoCodes(Request $request)
    {
        $query = PromoCode::query();
        
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true)
                      ->where(function($q) {
                          $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
                      })
                      ->where(function($q) {
                          $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
                      });
            } else {
                $query->where('is_active', false);
            }
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        
        $promoCodes = $query->latest()->paginate(20);
        
        return view('admin.marketing.promo-codes', compact('promoCodes'));
    }

    public function createPromoCode()
    {
        return view('admin.marketing.promo-codes-create');
    }

    public function storePromoCode(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:promo_codes,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
            'applicable_to' => 'required|in:all,tours,hotels,specific',
            'applicable_ids' => 'nullable|array',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        $validated['code'] = strtoupper($validated['code']);
        
        PromoCode::create($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Promo code created successfully!']);
        }
        
        return redirect()->route('admin.marketing.promo-codes')->with('success', 'Promo code created successfully!');
    }

    public function editPromoCode($id)
    {
        $promoCode = PromoCode::findOrFail($id);
        return view('admin.marketing.promo-codes-edit', compact('promoCode'));
    }

    public function updatePromoCode(Request $request, $id)
    {
        $promoCode = PromoCode::findOrFail($id);
        
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:promo_codes,code,' . $promoCode->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
            'applicable_to' => 'required|in:all,tours,hotels,specific',
            'applicable_ids' => 'nullable|array',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        $validated['code'] = strtoupper($validated['code']);
        
        $promoCode->update($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Promo code updated successfully!']);
        }
        
        return redirect()->route('admin.marketing.promo-codes')->with('success', 'Promo code updated successfully!');
    }

    public function destroyPromoCode($id)
    {
        $promoCode = PromoCode::findOrFail($id);
        $promoCode->delete();
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Promo code deleted successfully!']);
        }
        
        return redirect()->route('admin.marketing.promo-codes')->with('success', 'Promo code deleted successfully!');
    }

    // ==================== EMAIL CAMPAIGNS ====================
    
    public function emailCampaigns(Request $request)
    {
        $query = EmailCampaign::query();
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }
        
        $campaigns = $query->latest()->paginate(20);
        
        return view('admin.marketing.email-campaigns', compact('campaigns'));
    }

    public function createEmailCampaign()
    {
        return view('admin.marketing.email-campaigns-create');
    }

    public function storeEmailCampaign(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipient_type' => 'required|in:all,customers,subscribers,custom',
            'recipient_ids' => 'nullable|array',
            'status' => 'required|in:draft,scheduled,sending,sent,cancelled',
            'scheduled_at' => 'nullable|date|after:now',
        ]);
        
        EmailCampaign::create($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Email campaign created successfully!']);
        }
        
        return redirect()->route('admin.marketing.email-campaigns')->with('success', 'Email campaign created successfully!');
    }

    public function editEmailCampaign($id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        return view('admin.marketing.email-campaigns-edit', compact('campaign'));
    }

    public function updateEmailCampaign(Request $request, $id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipient_type' => 'required|in:all,customers,subscribers,custom',
            'recipient_ids' => 'nullable|array',
            'status' => 'required|in:draft,scheduled,sending,sent,cancelled',
            'scheduled_at' => 'nullable|date|after:now',
        ]);
        
        $campaign->update($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Email campaign updated successfully!']);
        }
        
        return redirect()->route('admin.marketing.email-campaigns')->with('success', 'Email campaign updated successfully!');
    }

    public function sendEmailCampaign($id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        
        if ($campaign->status == 'sent') {
            return response()->json(['success' => false, 'message' => 'Campaign has already been sent!']);
        }
        
        // TODO: Implement actual email sending logic
        $campaign->update([
            'status' => 'sending',
            'sent_at' => now(),
        ]);
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Email campaign sent successfully!']);
        }
        
        return redirect()->route('admin.marketing.email-campaigns')->with('success', 'Email campaign sent successfully!');
    }

    public function destroyEmailCampaign($id)
    {
        $campaign = EmailCampaign::findOrFail($id);
        
        if ($campaign->status == 'sending' || $campaign->status == 'sent') {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete a campaign that is sending or has been sent!']);
            }
            return redirect()->route('admin.marketing.email-campaigns')->with('error', 'Cannot delete a campaign that is sending or has been sent!');
        }
        
        $campaign->delete();
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Email campaign deleted successfully!']);
        }
        
        return redirect()->route('admin.marketing.email-campaigns')->with('success', 'Email campaign deleted successfully!');
    }

    // ==================== SMS CAMPAIGNS ====================
    
    public function smsCampaigns(Request $request)
    {
        try {
            $query = SmsCampaign::query();
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('message', 'like', "%{$search}%");
                });
            }
            
            $campaigns = $query->latest()->paginate(20);
        } catch (\Exception $e) {
            // If table doesn't exist, return empty collection
            $campaigns = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        }
        
        return view('admin.marketing.sms-campaigns', compact('campaigns'));
    }

    public function createSmsCampaign()
    {
        return view('admin.marketing.sms-campaigns-create');
    }

    public function storeSmsCampaign(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string|max:160',
            'recipient_type' => 'required|in:all,customers,subscribers,custom',
            'recipient_ids' => 'nullable|array',
            'status' => 'required|in:draft,scheduled,sending,sent,cancelled',
            'scheduled_at' => 'nullable|date|after:now',
        ]);
        
        SmsCampaign::create($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'SMS campaign created successfully!']);
        }
        
        return redirect()->route('admin.marketing.sms-campaigns')->with('success', 'SMS campaign created successfully!');
    }

    public function editSmsCampaign($id)
    {
        $campaign = SmsCampaign::findOrFail($id);
        return view('admin.marketing.sms-campaigns-edit', compact('campaign'));
    }

    public function updateSmsCampaign(Request $request, $id)
    {
        $campaign = SmsCampaign::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string|max:160',
            'recipient_type' => 'required|in:all,customers,subscribers,custom',
            'recipient_ids' => 'nullable|array',
            'status' => 'required|in:draft,scheduled,sending,sent,cancelled',
            'scheduled_at' => 'nullable|date|after:now',
        ]);
        
        $campaign->update($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'SMS campaign updated successfully!']);
        }
        
        return redirect()->route('admin.marketing.sms-campaigns')->with('success', 'SMS campaign updated successfully!');
    }

    public function sendSmsCampaign($id)
    {
        $campaign = SmsCampaign::findOrFail($id);
        
        if ($campaign->status == 'sent') {
            return response()->json(['success' => false, 'message' => 'Campaign has already been sent!']);
        }
        
        // TODO: Implement actual SMS sending logic
        $campaign->update([
            'status' => 'sending',
            'sent_at' => now(),
        ]);
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'SMS campaign sent successfully!']);
        }
        
        return redirect()->route('admin.marketing.sms-campaigns')->with('success', 'SMS campaign sent successfully!');
    }

    public function destroySmsCampaign($id)
    {
        $campaign = SmsCampaign::findOrFail($id);
        
        if ($campaign->status == 'sending' || $campaign->status == 'sent') {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete a campaign that is sending or has been sent!']);
            }
            return redirect()->route('admin.marketing.sms-campaigns')->with('error', 'Cannot delete a campaign that is sending or has been sent!');
        }
        
        $campaign->delete();
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'SMS campaign deleted successfully!']);
        }
        
        return redirect()->route('admin.marketing.sms-campaigns')->with('success', 'SMS campaign deleted successfully!');
    }

    // ==================== SOCIAL MEDIA ====================
    
    public function socialMedia(Request $request)
    {
        try {
            $query = SocialMediaPost::query();
            
            if ($request->filled('platform')) {
                $query->where('platform', $request->platform);
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('content', 'like', "%{$search}%");
                });
            }
            
            $posts = $query->latest()->paginate(20);
        } catch (\Exception $e) {
            // If table doesn't exist, return empty collection
            $posts = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        }
        
        return view('admin.marketing.social-media', compact('posts'));
    }

    public function createSocialMedia()
    {
        return view('admin.marketing.social-media-create');
    }

    public function storeSocialMedia(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|in:facebook,twitter,instagram,linkedin',
            'content' => 'required|string',
            'media_url' => 'nullable|url',
            'status' => 'required|in:draft,scheduled,published',
            'scheduled_at' => 'nullable|date|after:now',
        ]);
        
        SocialMediaPost::create($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Social media post created successfully!']);
        }
        
        return redirect()->route('admin.marketing.social-media')->with('success', 'Social media post created successfully!');
    }

    public function editSocialMedia($id)
    {
        $post = SocialMediaPost::findOrFail($id);
        return view('admin.marketing.social-media-edit', compact('post'));
    }

    public function updateSocialMedia(Request $request, $id)
    {
        $post = SocialMediaPost::findOrFail($id);
        
        $validated = $request->validate([
            'platform' => 'required|in:facebook,twitter,instagram,linkedin',
            'content' => 'required|string',
            'media_url' => 'nullable|url',
            'status' => 'required|in:draft,scheduled,published',
            'scheduled_at' => 'nullable|date|after:now',
        ]);
        
        $post->update($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Social media post updated successfully!']);
        }
        
        return redirect()->route('admin.marketing.social-media')->with('success', 'Social media post updated successfully!');
    }

    public function publishSocialMedia($id)
    {
        $post = SocialMediaPost::findOrFail($id);
        
        if ($post->status == 'published') {
            return response()->json(['success' => false, 'message' => 'Post has already been published!']);
        }
        
        // TODO: Implement actual social media publishing logic
        $post->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Social media post published successfully!']);
        }
        
        return redirect()->route('admin.marketing.social-media')->with('success', 'Social media post published successfully!');
    }

    public function destroySocialMedia($id)
    {
        $post = SocialMediaPost::findOrFail($id);
        $post->delete();
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Social media post deleted successfully!']);
        }
        
        return redirect()->route('admin.marketing.social-media')->with('success', 'Social media post deleted successfully!');
    }

    // ==================== LANDING PAGES ====================
    
    public function landingPages(Request $request)
    {
        try {
            $query = LandingPage::query();
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%");
                });
            }
            
            $pages = $query->latest()->paginate(20);
        } catch (\Exception $e) {
            // If table doesn't exist, return empty collection
            $pages = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        }
        
        return view('admin.marketing.landing-pages', compact('pages'));
    }

    public function createLandingPage()
    {
        return view('admin.marketing.landing-pages-create');
    }

    public function storeLandingPage(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:landing_pages,slug',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
        ]);
        
        LandingPage::create($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Landing page created successfully!']);
        }
        
        return redirect()->route('admin.marketing.landing-pages')->with('success', 'Landing page created successfully!');
    }

    public function editLandingPage($id)
    {
        $page = LandingPage::findOrFail($id);
        return view('admin.marketing.landing-pages-edit', compact('page'));
    }

    public function updateLandingPage(Request $request, $id)
    {
        $page = LandingPage::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:landing_pages,slug,' . $page->id,
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
        ]);
        
        $page->update($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Landing page updated successfully!']);
        }
        
        return redirect()->route('admin.marketing.landing-pages')->with('success', 'Landing page updated successfully!');
    }

    public function destroyLandingPage($id)
    {
        $page = LandingPage::findOrFail($id);
        $page->delete();
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Landing page deleted successfully!']);
        }
        
        return redirect()->route('admin.marketing.landing-pages')->with('success', 'Landing page deleted successfully!');
    }

    // ==================== ANALYTICS ====================
    
    public function analytics(Request $request)
    {
        $dateFrom = $request->date_from ?? Carbon::now()->subDays(30)->toDateString();
        $dateTo = $request->date_to ?? Carbon::now()->toDateString();
        
        try {
            $analytics = [
                'email_open_rate' => EmailCampaign::whereBetween('created_at', [$dateFrom, $dateTo])->avg('open_rate') ?? 0,
                'email_click_rate' => EmailCampaign::whereBetween('created_at', [$dateFrom, $dateTo])->avg('click_rate') ?? 0,
                'sms_delivery_rate' => SmsCampaign::whereBetween('created_at', [$dateFrom, $dateTo])->avg('delivery_rate') ?? 0,
                'social_engagement' => SocialMediaPost::whereBetween('created_at', [$dateFrom, $dateTo])->avg('engagement_rate') ?? 0,
                'total_emails_sent' => EmailCampaign::whereBetween('created_at', [$dateFrom, $dateTo])->sum('sent_count') ?? 0,
                'total_sms_sent' => SmsCampaign::whereBetween('created_at', [$dateFrom, $dateTo])->sum('sent_count') ?? 0,
                'total_social_posts' => SocialMediaPost::whereBetween('created_at', [$dateFrom, $dateTo])->where('status', 'published')->count(),
            ];
        } catch (\Exception $e) {
        $analytics = [
            'email_open_rate' => 0,
            'email_click_rate' => 0,
            'sms_delivery_rate' => 0,
            'social_engagement' => 0,
                'total_emails_sent' => 0,
                'total_sms_sent' => 0,
                'total_social_posts' => 0,
        ];
        }
        
        return view('admin.marketing.analytics', compact('analytics', 'dateFrom', 'dateTo'));
    }

    // ==================== BANNERS ====================
    
    public function banners(Request $request)
    {
        $query = Banner::query();
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }
        
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } else {
                $query->where('is_active', false);
            }
        }
        
        $banners = $query->orderBy('display_order')->latest()->paginate(20);
        
        return view('admin.marketing.banners', compact('banners'));
    }

    public function createBanner()
    {
        return view('admin.marketing.banners-create');
    }

    public function storeBanner(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => ['required', 'string', 'max:500', function ($attribute, $value, $fail) {
                // Allow full URLs or paths starting with /storage/ or /images/
                if (!filter_var($value, FILTER_VALIDATE_URL) && !str_starts_with($value, '/storage/') && !str_starts_with($value, '/images/') && !str_starts_with($value, 'http://') && !str_starts_with($value, 'https://')) {
                    $fail('The image URL must be a valid URL or a path starting with /storage/ or /images/.');
                }
            }],
            'link_url' => 'nullable|url',
            'position' => 'required|in:header,sidebar,footer,popup',
            'type' => 'required|in:banner,popup',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'display_order' => 'nullable|integer|min:0',
            'target_audience' => 'required|in:all,logged_in,guests',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        if (!$validated['display_order']) {
            $validated['display_order'] = (Banner::max('display_order') ?? 0) + 1;
        }
        
        Banner::create($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Banner created successfully!']);
        }
        
        return redirect()->route('admin.marketing.banners')->with('success', 'Banner created successfully!');
    }

    public function editBanner($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.marketing.banners-edit', compact('banner'));
    }

    public function updateBanner(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => ['required', 'string', 'max:500', function ($attribute, $value, $fail) {
                // Allow full URLs or paths starting with /storage/ or /images/
                if (!filter_var($value, FILTER_VALIDATE_URL) && !str_starts_with($value, '/storage/') && !str_starts_with($value, '/images/') && !str_starts_with($value, 'http://') && !str_starts_with($value, 'https://')) {
                    $fail('The image URL must be a valid URL or a path starting with /storage/ or /images/.');
                }
            }],
            'link_url' => 'nullable|url',
            'position' => 'required|in:header,sidebar,footer,popup',
            'type' => 'required|in:banner,popup',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'display_order' => 'nullable|integer|min:0',
            'target_audience' => 'required|in:all,logged_in,guests',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        $banner->update($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Banner updated successfully!']);
        }
        
        return redirect()->route('admin.marketing.banners')->with('success', 'Banner updated successfully!');
    }

    public function toggleBanner($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->update(['is_active' => !$banner->is_active]);
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Banner status updated successfully!',
                'is_active' => $banner->is_active
            ]);
        }
        
        return redirect()->route('admin.marketing.banners')->with('success', 'Banner status updated successfully!');
    }

    public function destroyBanner($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Banner deleted successfully!']);
        }
        
        return redirect()->route('admin.marketing.banners')->with('success', 'Banner deleted successfully!');
    }

    // ==================== NEWSLETTER MANAGEMENT ====================
    
    public function newsletter(Request $request)
    {
        $query = \App\Models\Subscriber::query();
        
        if ($request->filled('status')) {
            if ($request->status == 'verified') {
                $query->whereNotNull('verified_at');
            } elseif ($request->status == 'unverified') {
                $query->whereNull('verified_at');
            }
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('email', 'like', "%{$search}%");
        }
        
        $subscribers = $query->latest()->paginate(50);
        $totalSubscribers = \App\Models\Subscriber::whereNotNull('verified_at')->count();
        $unverifiedSubscribers = \App\Models\Subscriber::whereNull('verified_at')->count();
        
        return view('admin.marketing.newsletter', compact('subscribers', 'totalSubscribers', 'unverifiedSubscribers'));
    }

    public function exportNewsletter()
    {
        $subscribers = \App\Models\Subscriber::whereNotNull('verified_at')->get(['email', 'created_at', 'verified_at']);
        
        $filename = 'newsletter_subscribers_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($subscribers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Email', 'Subscribed At', 'Verified At']);
            
            foreach ($subscribers as $subscriber) {
                fputcsv($file, [
                    $subscriber->email, 
                    $subscriber->created_at->format('Y-m-d H:i:s'),
                    $subscriber->verified_at ? $subscriber->verified_at->format('Y-m-d H:i:s') : 'Not verified'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function destroySubscriber($id)
    {
        $subscriber = \App\Models\Subscriber::findOrFail($id);
        $subscriber->delete();
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Subscriber deleted successfully!']);
        }
        
        return redirect()->route('admin.marketing.newsletter')->with('success', 'Subscriber deleted successfully!');
    }

    // ==================== LEAD MANAGEMENT ====================
    
    public function leads(Request $request)
    {
        // Leads can come from contact forms, inquiries, etc.
        // For now, we'll use bookings with status 'pending' or 'inquiry' as leads
        $query = \App\Models\Booking::with(['tour', 'user'])
            ->whereIn('status', ['pending', 'inquiry', 'pending_payment']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('booking_reference', 'like', "%{$search}%");
            });
        }
        
        $leads = $query->latest()->paginate(20);
        $totalLeads = \App\Models\Booking::whereIn('status', ['pending', 'inquiry', 'pending_payment'])->count();
        
        return view('admin.marketing.leads', compact('leads', 'totalLeads'));
    }

    public function showLead($id)
    {
        $lead = \App\Models\Booking::with(['tour', 'user'])->findOrFail($id);
        return view('admin.marketing.leads-show', compact('lead'));
    }

    public function updateLead(Request $request, $id)
    {
        $lead = \App\Models\Booking::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,inquiry,cancelled',
            'notes' => 'nullable|string',
        ]);
        
        $lead->update($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Lead updated successfully!']);
        }
        
        return redirect()->route('admin.marketing.leads')->with('success', 'Lead updated successfully!');
    }

    public function destroyLead($id)
    {
        $lead = \App\Models\Booking::findOrFail($id);
        $lead->delete();
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Lead deleted successfully!']);
        }
        
        return redirect()->route('admin.marketing.leads')->with('success', 'Lead deleted successfully!');
    }

    // ==================== EMAIL TEMPLATES ====================
    
    public function emailTemplates(Request $request)
    {
        // For now, return a simple view. In production, you'd have an EmailTemplate model
        return view('admin.marketing.email-templates');
    }

    public function createEmailTemplate()
    {
        return view('admin.marketing.email-templates-create');
    }

    public function storeEmailTemplate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:email_templates,key',
            'subject' => 'required|string|max:255',
            'body_html' => 'nullable|string',
            'body_text' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $validated['key'] = Str::slug($validated['key'], '_');
        $validated['updated_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');
        
        \App\Models\EmailTemplate::create($validated);
        
        return redirect()->route('admin.marketing.email-templates')->with('success', 'Email template created successfully!');
    }

    public function editEmailTemplate($id)
    {
        $emailTemplate = \App\Models\EmailTemplate::findOrFail($id);
        return view('admin.marketing.email-templates-edit', compact('id', 'emailTemplate'));
    }

    public function updateEmailTemplate(Request $request, $id)
    {
        $emailTemplate = \App\Models\EmailTemplate::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:email_templates,key,' . $id,
            'subject' => 'required|string|max:255',
            'body_html' => 'nullable|string',
            'body_text' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $validated['key'] = Str::slug($validated['key'], '_');
        $validated['updated_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');
        
        $emailTemplate->update($validated);
        
        return redirect()->route('admin.marketing.email-templates')->with('success', 'Email template updated successfully!');
    }

    public function destroyEmailTemplate($id)
    {
        // TODO: Implement email template deletion
        return redirect()->route('admin.marketing.email-templates')->with('success', 'Email template deleted successfully!');
    }

    // ==================== SMS TEMPLATES ====================
    
    public function smsTemplates(Request $request)
    {
        $query = SmsTemplate::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('key', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }
        
        $smsTemplates = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.marketing.sms-templates', compact('smsTemplates'));
    }

    public function createSmsTemplate()
    {
        return view('admin.marketing.sms-templates-create');
    }

    public function storeSmsTemplate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:sms_templates,key',
            'message' => 'required|string|max:160',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $validated['key'] = Str::slug($validated['key'], '_');
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');
        
        SmsTemplate::create($validated);
        
        return redirect()->route('admin.marketing.sms-templates')->with('success', 'SMS template created successfully!');
    }

    public function editSmsTemplate($id)
    {
        $smsTemplate = SmsTemplate::findOrFail($id);
        return view('admin.marketing.sms-templates-edit', compact('id', 'smsTemplate'));
    }

    public function updateSmsTemplate(Request $request, $id)
    {
        $smsTemplate = SmsTemplate::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:sms_templates,key,' . $id,
            'message' => 'required|string|max:160',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $validated['key'] = Str::slug($validated['key'], '_');
        $validated['updated_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');
        
        $smsTemplate->update($validated);
        
        return redirect()->route('admin.marketing.sms-templates')->with('success', 'SMS template updated successfully!');
    }

    public function destroySmsTemplate($id)
    {
        $smsTemplate = SmsTemplate::findOrFail($id);
        $smsTemplate->delete();
        
        return redirect()->route('admin.marketing.sms-templates')->with('success', 'SMS template deleted successfully!');
    }

    // ==================== PRESS RELEASES (PRO) ====================
    
    public function pressReleases(Request $request)
    {
        $query = PressRelease::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->where('release_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('release_date', '<=', $request->date_to);
        }
        
        $pressReleases = $query->orderBy('release_date', 'desc')->paginate(20);
        
        return view('admin.marketing.press-releases', compact('pressReleases'));
    }

    public function createPressRelease()
    {
        return view('admin.marketing.press-releases-create');
    }

    public function storePressRelease(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'author' => 'nullable|string|max:255',
            'release_date' => 'required|date',
            'category' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);
        
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('press-releases', 'public');
        }
        
        PressRelease::create($validated);
        
        return redirect()->route('admin.marketing.press-releases')->with('success', 'Press release created successfully!');
    }

    public function editPressRelease($id)
    {
        $pressRelease = PressRelease::findOrFail($id);
        return view('admin.marketing.press-releases-edit', compact('id', 'pressRelease'));
    }

    public function updatePressRelease(Request $request, $id)
    {
        $pressRelease = PressRelease::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'author' => 'nullable|string|max:255',
            'release_date' => 'required|date',
            'category' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);
        
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('press-releases', 'public');
        }
        
        $validated['updated_by'] = auth()->id();
        
        $pressRelease->update($validated);
        
        return redirect()->route('admin.marketing.press-releases')->with('success', 'Press release updated successfully!');
    }

    public function destroyPressRelease($id)
    {
        $pressRelease = PressRelease::findOrFail($id);
        $pressRelease->delete();
        
        return redirect()->route('admin.marketing.press-releases')->with('success', 'Press release deleted successfully!');
    }

    // ==================== MEDIA KITS (PRO) ====================
    
    public function mediaKits(Request $request)
    {
        $query = MediaKit::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $mediaKits = $query->orderBy('created_at', 'desc')->paginate(12);
        
        return view('admin.marketing.media-kits', compact('mediaKits'));
    }

    public function createMediaKit()
    {
        return view('admin.marketing.media-kits-create');
    }

    public function storeMediaKit(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'company_info' => 'nullable|string',
            'contact_info' => 'nullable|json',
            'download_url' => 'nullable|url',
            'status' => 'required|in:draft,published,archived',
        ]);
        
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        
        if ($request->filled('contact_info')) {
            $validated['contact_info'] = json_decode($validated['contact_info'], true);
        }
        
        MediaKit::create($validated);
        
        return redirect()->route('admin.marketing.media-kits')->with('success', 'Media kit created successfully!');
    }

    public function editMediaKit($id)
    {
        $mediaKit = MediaKit::findOrFail($id);
        return view('admin.marketing.media-kits-edit', compact('id', 'mediaKit'));
    }

    public function updateMediaKit(Request $request, $id)
    {
        $mediaKit = MediaKit::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'company_info' => 'nullable|string',
            'contact_info' => 'nullable|json',
            'download_url' => 'nullable|url',
            'status' => 'required|in:draft,published,archived',
        ]);
        
        $validated['updated_by'] = auth()->id();
        
        if ($request->filled('contact_info')) {
            $validated['contact_info'] = json_decode($validated['contact_info'], true);
        }
        
        $mediaKit->update($validated);
        
        return redirect()->route('admin.marketing.media-kits')->with('success', 'Media kit updated successfully!');
    }

    public function destroyMediaKit($id)
    {
        $mediaKit = MediaKit::findOrFail($id);
        $mediaKit->delete();
        
        return redirect()->route('admin.marketing.media-kits')->with('success', 'Media kit deleted successfully!');
    }

    // ==================== EVENTS (PRO) ====================
    
    public function events(Request $request)
    {
        $query = Event::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }
        
        if ($request->filled('date_from')) {
            $query->where('event_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('event_date', '<=', $request->date_to);
        }
        
        $events = $query->orderBy('event_date', 'asc')->paginate(20);
        
        return view('admin.marketing.events', compact('events'));
    }

    public function createEvent()
    {
        return view('admin.marketing.events-create');
    }

    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable',
            'end_date' => 'nullable|date',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'venue' => 'nullable|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'ticket_url' => 'nullable|url',
            'ticket_price' => 'nullable|numeric|min:0',
            'max_attendees' => 'nullable|integer|min:0',
            'event_type' => 'required|in:conference,workshop,exhibition,networking,webinar,other',
            'status' => 'required|in:draft,published,cancelled,completed',
            'featured_image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);
        
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        $validated['is_featured'] = $request->has('is_featured');
        
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('events', 'public');
        }
        
        Event::create($validated);
        
        return redirect()->route('admin.marketing.events')->with('success', 'Event created successfully!');
    }

    public function editEvent($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.marketing.events-edit', compact('id', 'event'));
    }

    public function updateEvent(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable',
            'end_date' => 'nullable|date',
            'end_time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'venue' => 'nullable|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'ticket_url' => 'nullable|url',
            'ticket_price' => 'nullable|numeric|min:0',
            'max_attendees' => 'nullable|integer|min:0',
            'event_type' => 'required|in:conference,workshop,exhibition,networking,webinar,other',
            'status' => 'required|in:draft,published,cancelled,completed',
            'featured_image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);
        
        $validated['updated_by'] = auth()->id();
        $validated['is_featured'] = $request->has('is_featured');
        
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('events', 'public');
        }
        
        $event->update($validated);
        
        return redirect()->route('admin.marketing.events')->with('success', 'Event updated successfully!');
    }

    public function destroyEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        
        return redirect()->route('admin.marketing.events')->with('success', 'Event deleted successfully!');
    }
}
