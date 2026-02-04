<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactPageSection;
use App\Models\ContactPageFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactPageController extends BaseAdminController
{
    /**
     * Display the contact page management dashboard
     */
    public function index()
    {
        $sections = ContactPageSection::orderBy('display_order')->get();
        $features = ContactPageFeature::orderBy('display_order')->get();
        
        return view('admin.contact-page.index', compact('sections', 'features'));
    }

    /**
     * Update a section
     */
    public function updateSection(Request $request, $id)
    {
        try {
            $section = ContactPageSection::findOrFail($id);
            
            $validated = $request->validate([
                'section_name' => 'required|string|max:255',
                'content' => 'nullable|string',
                'data_json' => 'nullable|string',
                'image_url' => 'nullable|string|max:2000',
                'display_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|in:0,1,true,false',
            ]);
            
            $decodedData = null;
            if ($request->filled('data_json')) {
                $decodedData = json_decode($request->input('data_json'), true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json(['success' => false, 'message' => 'Invalid JSON provided for data.'], 422);
                    }
                    return back()->withErrors(['data_json' => 'Invalid JSON provided for data.'])->withInput();
                }
            }
            
            $payload = [
                'section_name' => $validated['section_name'],
                'content' => $validated['content'] ?? null,
                'image_url' => $validated['image_url'] ?? null,
                'display_order' => $validated['display_order'] ?? $section->display_order,
                'is_active' => filter_var($request->input('is_active', 0), FILTER_VALIDATE_BOOLEAN),
                'data' => $decodedData,
            ];
            
            $section->update($payload);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Section updated successfully!']);
            }
            
            return $this->successResponse('Section updated successfully!', route('admin.contact-page.index'));
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Store a new feature
     */
    public function storeFeature(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'image_url' => 'nullable|string|max:2000',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        
        $validated['display_order'] = $validated['display_order'] ?? (ContactPageFeature::max('display_order') ?? 0) + 1;
        $validated['is_active'] = $request->has('is_active') ? ($validated['is_active'] ?? true) : false;
        
        ContactPageFeature::create($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Feature added successfully!']);
        }
        
        return $this->successResponse('Feature added successfully!', route('admin.contact-page.index'));
    }

    /**
     * Update a feature
     */
    public function updateFeature(Request $request, $id)
    {
        $feature = ContactPageFeature::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'image_url' => 'nullable|string|max:2000',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active') ? ($validated['is_active'] ?? true) : false;
        
        $feature->update($validated);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Feature updated successfully!']);
        }
        
        return $this->successResponse('Feature updated successfully!', route('admin.contact-page.index'));
    }

    /**
     * Delete a feature
     */
    public function deleteFeature($id)
    {
        $feature = ContactPageFeature::findOrFail($id);
        $feature->delete();
        
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Feature deleted successfully!']);
        }
        
        return $this->successResponse('Feature deleted successfully!', route('admin.contact-page.index'));
    }

    /**
     * Update display orders
     */
    public function updateDisplayOrders(Request $request)
    {
        $request->validate([
            'features' => 'nullable|array',
            'features.*.id' => 'required|exists:contact_page_features,id',
            'features.*.order' => 'required|integer|min:0',
        ]);
        
        if ($request->has('features')) {
            foreach ($request->input('features') as $item) {
                ContactPageFeature::where('id', $item['id'])->update(['display_order' => $item['order']]);
            }
        }
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Display orders updated successfully!']);
        }
        
        return $this->successResponse('Display orders updated successfully!', route('admin.contact-page.index'));
    }
}
