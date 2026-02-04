<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CloudinaryAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CloudinaryAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = CloudinaryAccount::orderBy('is_default', 'desc')
            ->orderBy('is_active', 'desc')
            ->orderBy('name')
            ->get();

        return view('admin.cloudinary-accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cloudinary-accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cloud_name' => 'required|string|max:255',
            'api_key' => 'required|string|max:255',
            'api_secret' => 'required|string|max:255',
            'cloudinary_url' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        // If this is set as default, unset others
        if ($request->filled('is_default') && $request->is_default) {
            CloudinaryAccount::where('is_default', true)->update(['is_default' => false]);
        }

        $validated['created_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');
        $validated['is_default'] = $request->has('is_default');

        $account = CloudinaryAccount::create($validated);

        // Test connection automatically
        $testResult = $account->testConnection();

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cloudinary account created successfully',
                'account' => $account,
                'connection_test' => $testResult,
            ]);
        }

        return redirect()->route('admin.cloudinary-accounts.index')
            ->with('success', 'Cloudinary account created successfully. ' . $testResult['message']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $account = CloudinaryAccount::findOrFail($id);
        return view('admin.cloudinary-accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $account = CloudinaryAccount::findOrFail($id);
        return view('admin.cloudinary-accounts.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $account = CloudinaryAccount::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cloud_name' => 'required|string|max:255',
            'api_key' => 'required|string|max:255',
            'api_secret' => 'required|string|max:255',
            'cloudinary_url' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        // If this is set as default, unset others
        if ($request->filled('is_default') && $request->is_default && !$account->is_default) {
            CloudinaryAccount::where('id', '!=', $id)->where('is_default', true)->update(['is_default' => false]);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_default'] = $request->has('is_default');

        $account->update($validated);

        // Test connection automatically
        $testResult = $account->testConnection();

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cloudinary account updated successfully',
                'account' => $account,
                'connection_test' => $testResult,
            ]);
        }

        return redirect()->route('admin.cloudinary-accounts.index')
            ->with('success', 'Cloudinary account updated successfully. ' . $testResult['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $account = CloudinaryAccount::findOrFail($id);
        $account->delete();

        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cloudinary account deleted successfully',
            ]);
        }

        return redirect()->route('admin.cloudinary-accounts.index')
            ->with('success', 'Cloudinary account deleted successfully');
    }

    /**
     * Test connection for an account
     */
    public function testConnection($id)
    {
        $account = CloudinaryAccount::findOrFail($id);
        $result = $account->testConnection();

        return response()->json($result);
    }

    /**
     * Set default account
     */
    public function setDefault($id)
    {
        DB::transaction(function () use ($id) {
            CloudinaryAccount::where('is_default', true)->update(['is_default' => false]);
            $account = CloudinaryAccount::findOrFail($id);
            $account->update(['is_default' => true]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Default account updated successfully',
        ]);
    }
}
