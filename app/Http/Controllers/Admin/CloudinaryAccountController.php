<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CloudinaryAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

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
        try {
            // Check if table exists
            if (!\Illuminate\Support\Facades\Schema::hasTable('cloudinary_accounts')) {
                throw new \Exception('cloudinary_accounts table does not exist. Please run migrations: php artisan migrate');
            }

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

            DB::beginTransaction();

            // If this is set as default, unset others
            if ($request->filled('is_default') && $request->is_default) {
                CloudinaryAccount::where('is_default', true)->update(['is_default' => false]);
            }

            $validated['created_by'] = auth()->id();
            $validated['is_active'] = $request->has('is_active') ? true : false;
            $validated['is_default'] = $request->has('is_default') ? true : false;

            // Remove any fields that don't exist in fillable or table
            $fillable = (new CloudinaryAccount())->getFillable();
            $validated = array_intersect_key($validated, array_flip($fillable));

            // Remove null values for optional fields to use defaults
            if (empty($validated['cloudinary_url'])) {
                unset($validated['cloudinary_url']);
            }
            if (empty($validated['description'])) {
                unset($validated['description']);
            }

            Log::info('Creating Cloudinary account', ['data' => array_merge($validated, ['api_secret' => '***hidden***'])]);

            try {
                $account = CloudinaryAccount::create($validated);
            } catch (\Illuminate\Database\QueryException $e) {
                Log::error('Database error creating Cloudinary account', [
                    'error' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'sql' => $e->getSql() ?? 'N/A'
                ]);
                throw new \Exception('Database error: ' . $e->getMessage() . '. Please check if the cloudinary_accounts table exists and migrations are up to date.');
            }

            DB::commit();

            \Log::info('Cloudinary account created successfully', ['account_id' => $account->id]);

            // Test connection automatically (don't fail if test fails)
            $testResult = ['success' => false, 'message' => 'Connection test skipped'];
            try {
                $testResult = $account->testConnection();
            } catch (\Exception $e) {
                \Log::warning('Cloudinary connection test failed after save', [
                    'account_id' => $account->id,
                    'error' => $e->getMessage()
                ]);
                $testResult = ['success' => false, 'message' => 'Connection test failed: ' . $e->getMessage()];
            }

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cloudinary account created successfully',
                    'account' => $account,
                    'connection_test' => $testResult,
                ]);
            }

            $message = 'Cloudinary account created successfully.';
            if ($testResult['success']) {
                $message .= ' ' . $testResult['message'];
            } else {
                $message .= ' Note: ' . $testResult['message'];
            }

            return redirect()->route('admin.cloudinary-accounts.index')
                ->with('success', $message);
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to save Cloudinary account', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save account: ' . $e->getMessage(),
                ], 500);
            }

            $errorMessage = 'Failed to save account: ' . $e->getMessage();
            
            // If it's a database error, provide more helpful message
            if (strpos($e->getMessage(), 'Table') !== false || strpos($e->getMessage(), 'doesn\'t exist') !== false) {
                $errorMessage = 'Database table not found. Please run: php artisan migrate';
            } elseif (strpos($e->getMessage(), 'SQLSTATE') !== false) {
                $errorMessage = 'Database error occurred. Please check your database connection and table structure.';
            }
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $errorMessage])
                ->with('error', $errorMessage);
        }
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
        try {
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

            DB::beginTransaction();

            // If this is set as default, unset others
            if ($request->filled('is_default') && $request->is_default && !$account->is_default) {
                CloudinaryAccount::where('id', '!=', $id)->where('is_default', true)->update(['is_default' => false]);
            }

            $validated['is_active'] = $request->has('is_active') ? true : false;
            $validated['is_default'] = $request->has('is_default') ? true : false;

            // Remove any fields that don't exist in fillable
            $fillable = (new CloudinaryAccount())->getFillable();
            $validated = array_intersect_key($validated, array_flip($fillable));

            // Remove null values for optional fields
            if (empty($validated['cloudinary_url'])) {
                $validated['cloudinary_url'] = null;
            }
            if (empty($validated['description'])) {
                $validated['description'] = null;
            }

            Log::info('Updating Cloudinary account', ['account_id' => $id, 'data' => array_merge($validated, ['api_secret' => '***hidden***'])]);

            try {
                $account->update($validated);
            } catch (\Illuminate\Database\QueryException $e) {
                Log::error('Database error updating Cloudinary account', [
                    'account_id' => $id,
                    'error' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'sql' => $e->getSql() ?? 'N/A'
                ]);
                throw new \Exception('Database error: ' . $e->getMessage());
            }

            DB::commit();

            // Test connection automatically (don't fail if test fails)
            $testResult = ['success' => false, 'message' => 'Connection test skipped'];
            try {
                $testResult = $account->testConnection();
            } catch (\Exception $e) {
                \Log::warning('Cloudinary connection test failed after update', [
                    'account_id' => $account->id,
                    'error' => $e->getMessage()
                ]);
                $testResult = ['success' => false, 'message' => 'Connection test failed: ' . $e->getMessage()];
            }

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cloudinary account updated successfully',
                    'account' => $account,
                    'connection_test' => $testResult,
                ]);
            }

            $message = 'Cloudinary account updated successfully.';
            if ($testResult['success']) {
                $message .= ' ' . $testResult['message'];
            } else {
                $message .= ' Note: ' . $testResult['message'];
            }

            return redirect()->route('admin.cloudinary-accounts.index')
                ->with('success', $message);
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update Cloudinary account', [
                'account_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update account: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update account: ' . $e->getMessage()]);
        }
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
