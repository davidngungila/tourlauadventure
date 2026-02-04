<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CloudinaryController extends Controller
{
    protected function getAccount($accountId = null)
    {
        if ($accountId) {
            $account = \App\Models\CloudinaryAccount::find($accountId);
            if ($account && $account->is_active) {
                return $account;
            }
        }

        // Get default account
        return \App\Models\CloudinaryAccount::getDefault();
    }

    protected function getCredentials($account = null)
    {
        if (!$account) {
            $account = $this->getAccount();
        }

        if ($account) {
            return [
                'cloud_name' => $account->cloud_name,
                'api_key' => $account->api_key,
                'api_secret' => $account->api_secret,
                'base_url' => "https://api.cloudinary.com/v1_1/{$account->cloud_name}",
            ];
        }

        // Fallback to env variables
        $cloudinaryUrl = env('CLOUDINARY_URL');
        
        if ($cloudinaryUrl) {
            $parsed = parse_url($cloudinaryUrl);
            $cloudName = $parsed['host'] ?? null;
            $apiKey = $parsed['user'] ?? null;
            $apiSecret = $parsed['pass'] ?? null;
        } else {
            $cloudName = env('CLOUDINARY_CLOUD_NAME');
            $apiKey = env('CLOUDINARY_API_KEY');
            $apiSecret = env('CLOUDINARY_API_SECRET');
        }

        return [
            'cloud_name' => $cloudName,
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'base_url' => $cloudName ? "https://api.cloudinary.com/v1_1/{$cloudName}" : null,
        ];
    }

    /**
     * Display Cloudinary management page
     */
    public function index()
    {
        return view('admin.cloudinary.index');
    }

    /**
     * Get Cloudinary assets
     */
    public function getAssets(Request $request)
    {
        try {
            $accountId = $request->get('account_id');
            $account = $this->getAccount($accountId);
            $credentials = $this->getCredentials($account);

            if (!$credentials['cloud_name'] || !$credentials['api_key'] || !$credentials['api_secret']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cloudinary credentials not configured. Please add an account in Cloudinary Accounts settings.'
                ], 400);
            }

            $resourceType = $request->get('resource_type', 'image');
            
            $params = [
                'max_results' => $request->get('max_results', 500),
                'type' => 'upload',
            ];

            if ($request->filled('folder')) {
                $params['prefix'] = $request->folder;
            }

            if ($request->filled('next_cursor')) {
                $params['next_cursor'] = $request->next_cursor;
            }

            // Generate signature for authentication
            $timestamp = time();
            $params['timestamp'] = $timestamp;
            $signature = $this->generateSignature($params, $timestamp, $credentials['api_secret']);
            $params['signature'] = $signature;
            $params['api_key'] = $credentials['api_key'];

            // Use the correct API endpoint based on resource type
            $endpoint = "{$credentials['base_url']}/resources/{$resourceType}/upload";
            
            Log::info('Cloudinary getAssets request', [
                'endpoint' => $endpoint,
                'params' => array_merge($params, ['api_secret' => '***hidden***']),
                'account_id' => $account?->id,
            ]);

            $response = Http::get($endpoint, $params);

            if ($response->successful()) {
                $data = $response->json();
                
                $resources = collect($data['resources'] ?? [])->map(function ($resource) {
                    return [
                        'public_id' => $resource['public_id'],
                        'filename' => basename($resource['public_id']),
                        'url' => $resource['secure_url'] ?? $resource['url'] ?? '',
                        'secure_url' => $resource['secure_url'] ?? $resource['url'] ?? '', // Keep for backward compatibility
                        'width' => $resource['width'] ?? null,
                        'height' => $resource['height'] ?? null,
                        'format' => $resource['format'] ?? null,
                        'bytes' => $resource['bytes'] ?? 0,
                        'folder' => $resource['folder'] ?? '',
                        'created_at' => $resource['created_at'] ?? null,
                        'resource_type' => $resource['resource_type'] ?? 'image',
                    ];
                });

                return response()->json([
                    'success' => true,
                    'resources' => $resources,
                    'next_cursor' => $data['next_cursor'] ?? null,
                    'total_count' => $data['total_count'] ?? $resources->count(),
                    'account_id' => $account?->id,
                    'account_name' => $account?->name,
                ]);
            }

            // Get detailed error information
            $errorBody = $response->body();
            $errorJson = $response->json();
            $errorMessage = 'Failed to fetch assets from Cloudinary';
            
            if (isset($errorJson['error']['message'])) {
                $errorMessage = $errorJson['error']['message'];
            } elseif (is_string($errorBody)) {
                $errorMessage = $errorBody;
            }

            Log::error('Cloudinary getAssets API error', [
                'status' => $response->status(),
                'error' => $errorMessage,
                'response' => $errorBody,
                'account_id' => $account?->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'error' => $errorBody,
                'status' => $response->status(),
                'account_id' => $account?->id,
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Cloudinary getAssets error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching Cloudinary assets: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Cloudinary folders
     */
    public function getFolders(Request $request)
    {
        try {
            $accountId = $request->get('account_id');
            $account = $this->getAccount($accountId);
            $credentials = $this->getCredentials($account);

            if (!$credentials['cloud_name'] || !$credentials['api_key'] || !$credentials['api_secret']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cloudinary credentials not configured'
                ], 400);
            }

            $timestamp = time();
            $params = [
                'timestamp' => $timestamp,
            ];
            $signature = $this->generateSignature($params, $timestamp, $credentials['api_secret']);
            $params['signature'] = $signature;
            $params['api_key'] = $credentials['api_key'];

            $endpoint = "{$credentials['base_url']}/folders";
            
            Log::info('Cloudinary getFolders request', [
                'endpoint' => $endpoint,
                'params' => array_merge($params, ['api_secret' => '***hidden***']),
                'account_id' => $account?->id,
            ]);

            $response = Http::get($endpoint, $params);

            if ($response->successful()) {
                $data = $response->json();
                $folders = collect($data['folders'] ?? [])->map(function ($folder) {
                    return [
                        'path' => $folder['path'],
                        'name' => basename($folder['path']),
                    ];
                });

                return response()->json([
                    'success' => true,
                    'folders' => $folders,
                ]);
            }

            // Get detailed error information
            $errorBody = $response->body();
            $errorJson = $response->json();
            $errorMessage = 'Failed to fetch folders from Cloudinary';
            
            if (isset($errorJson['error']['message'])) {
                $errorMessage = $errorJson['error']['message'];
            } elseif (is_string($errorBody)) {
                $errorMessage = $errorBody;
            }

            Log::error('Cloudinary getFolders API error', [
                'status' => $response->status(),
                'error' => $errorMessage,
                'response' => $errorBody,
                'account_id' => $account?->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'error' => $errorBody,
                'status' => $response->status(),
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Cloudinary getFolders error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching folders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload file to Cloudinary
     */
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|image|max:10240', // 10MB max
                'folder' => 'nullable|string',
                'account_id' => 'nullable|integer|exists:cloudinary_accounts,id',
            ]);

            $accountId = $request->get('account_id');
            $account = $this->getAccount($accountId);
            $credentials = $this->getCredentials($account);

            if (!$credentials['cloud_name'] || !$credentials['api_key'] || !$credentials['api_secret']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cloudinary credentials not configured'
                ], 400);
            }

            $file = $request->file('file');
            $folder = $request->input('folder', '');

            $timestamp = time();
            $params = [
                'timestamp' => $timestamp,
                'folder' => $folder,
            ];
            $signature = $this->generateSignature($params, $timestamp, $credentials['api_secret']);

            $response = Http::attach(
                'file', file_get_contents($file->getRealPath()), $file->getClientOriginalName()
            )->post("{$credentials['base_url']}/image/upload", [
                'api_key' => $credentials['api_key'],
                'timestamp' => $timestamp,
                'signature' => $signature,
                'folder' => $folder,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'message' => 'File uploaded successfully',
                    'asset' => [
                        'public_id' => $data['public_id'],
                        'url' => $data['secure_url'] ?? $data['url'],
                        'width' => $data['width'],
                        'height' => $data['height'],
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Upload failed',
                'error' => $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Cloudinary upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Upload error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete asset from Cloudinary
     */
    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'public_id' => 'required|string',
                'account_id' => 'nullable|integer|exists:cloudinary_accounts,id',
            ]);

            $accountId = $request->get('account_id');
            $account = $this->getAccount($accountId);
            $credentials = $this->getCredentials($account);

            if (!$credentials['cloud_name'] || !$credentials['api_key'] || !$credentials['api_secret']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cloudinary credentials not configured'
                ], 400);
            }

            $publicId = $request->input('public_id');
            $timestamp = time();
            $params = [
                'public_id' => $publicId,
                'timestamp' => $timestamp,
            ];
            $signature = $this->generateSignature($params, $timestamp, $credentials['api_secret']);

            $response = Http::asForm()->post("{$credentials['base_url']}/image/destroy", [
                'public_id' => $publicId,
                'timestamp' => $timestamp,
                'signature' => $signature,
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Asset deleted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Delete failed'
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Cloudinary delete error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Delete error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rename asset in Cloudinary
     */
    public function rename(Request $request)
    {
        try {
            $request->validate([
                'from_public_id' => 'required|string',
                'to_public_id' => 'required|string',
            ]);

            if (!$this->cloudName || !$this->apiKey || !$this->apiSecret) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cloudinary credentials not configured'
                ], 400);
            }

            $timestamp = time();
            $params = [
                'from_public_id' => $request->input('from_public_id'),
                'to_public_id' => $request->input('to_public_id'),
                'timestamp' => $timestamp,
            ];
            $signature = $this->generateSignature($params, $timestamp);

            $response = Http::withBasicAuth($this->apiKey, $this->apiSecret)
                ->post("{$this->baseUrl}/rename", array_merge($params, [
                    'signature' => $signature,
                ]));

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Asset renamed successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Rename failed'
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Cloudinary rename error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Rename error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create folder in Cloudinary
     */
    public function createFolder(Request $request)
    {
        try {
            $request->validate([
                'folder_path' => 'required|string',
            ]);

            // Cloudinary doesn't have explicit folder creation API
            // Folders are created automatically when uploading with folder parameter
            return response()->json([
                'success' => true,
                'message' => 'Folder will be created when you upload files to it'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import Cloudinary asset to gallery
     */
    public function importToGallery(Request $request)
    {
        try {
            $request->validate([
                'public_id' => 'required|string',
                'url' => 'required|url',
            ]);

            $gallery = \App\Models\Gallery::create([
                'title' => basename($request->input('public_id')),
                'image_url' => $request->input('url'),
                'category' => $request->input('category', 'Cloudinary Import'),
                'is_active' => true,
                'uploaded_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Image imported to gallery successfully',
                'gallery' => $gallery
            ]);

        } catch (\Exception $e) {
            Log::error('Cloudinary import error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Import error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate Cloudinary API signature
     */
    protected function generateSignature(array $params, int $timestamp, string $apiSecret): string
    {
        $params['timestamp'] = $timestamp;
        ksort($params);
        $signatureString = http_build_query($params);
        return sha1($signatureString . $apiSecret);
    }
}
