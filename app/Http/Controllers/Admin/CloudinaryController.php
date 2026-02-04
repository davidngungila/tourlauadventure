<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CloudinaryController extends Controller
{
    protected $cloudName;
    protected $apiKey;
    protected $apiSecret;
    protected $baseUrl;

    public function __construct()
    {
        $cloudinaryUrl = env('CLOUDINARY_URL');
        
        if ($cloudinaryUrl) {
            // Parse CLOUDINARY_URL: cloudinary://api_key:api_secret@cloud_name
            $parsed = parse_url($cloudinaryUrl);
            $this->cloudName = $parsed['host'] ?? null;
            $this->apiKey = $parsed['user'] ?? null;
            $this->apiSecret = $parsed['pass'] ?? null;
        } else {
            // Fallback to individual env variables
            $this->cloudName = env('CLOUDINARY_CLOUD_NAME');
            $this->apiKey = env('CLOUDINARY_API_KEY');
            $this->apiSecret = env('CLOUDINARY_API_SECRET');
        }
        
        $this->baseUrl = "https://api.cloudinary.com/v1_1/{$this->cloudName}";
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
            if (!$this->cloudName || !$this->apiKey || !$this->apiSecret) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cloudinary credentials not configured. Please set CLOUDINARY_URL in .env'
                ], 400);
            }

            $params = [
                'resource_type' => 'image',
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
            $signature = $this->generateSignature($params, $timestamp);

            $response = Http::withBasicAuth($this->apiKey, $this->apiSecret)
                ->get("{$this->baseUrl}/resources/image/upload", array_merge($params, [
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                ]));

            if ($response->successful()) {
                $data = $response->json();
                
                $resources = collect($data['resources'] ?? [])->map(function ($resource) {
                    return [
                        'public_id' => $resource['public_id'],
                        'filename' => basename($resource['public_id']),
                        'url' => $resource['secure_url'] ?? $resource['url'] ?? '',
                        'width' => $resource['width'] ?? null,
                        'height' => $resource['height'] ?? null,
                        'format' => $resource['format'] ?? null,
                        'bytes' => $resource['bytes'] ?? 0,
                        'folder' => $resource['folder'] ?? '',
                        'created_at' => $resource['created_at'] ?? null,
                    ];
                });

                return response()->json([
                    'success' => true,
                    'resources' => $resources,
                    'next_cursor' => $data['next_cursor'] ?? null,
                    'total_count' => $data['total_count'] ?? $resources->count(),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch assets from Cloudinary',
                'error' => $response->body()
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
            if (!$this->cloudName || !$this->apiKey || !$this->apiSecret) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cloudinary credentials not configured'
                ], 400);
            }

            $timestamp = time();
            $params = ['timestamp' => $timestamp];
            $signature = $this->generateSignature($params, $timestamp);

            $response = Http::withBasicAuth($this->apiKey, $this->apiSecret)
                ->get("{$this->baseUrl}/folders", [
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                ]);

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

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch folders',
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
            ]);

            if (!$this->cloudName || !$this->apiKey || !$this->apiSecret) {
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
            $signature = $this->generateSignature($params, $timestamp);

            $response = Http::attach(
                'file', file_get_contents($file->getRealPath()), $file->getClientOriginalName()
            )->post("{$this->baseUrl}/image/upload", [
                'api_key' => $this->apiKey,
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
            ]);

            if (!$this->cloudName || !$this->apiKey || !$this->apiSecret) {
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
            $signature = $this->generateSignature($params, $timestamp);

            $response = Http::asForm()->post("{$this->baseUrl}/image/destroy", [
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
    protected function generateSignature(array $params, int $timestamp): string
    {
        $params['timestamp'] = $timestamp;
        ksort($params);
        $signatureString = http_build_query($params);
        return sha1($signatureString . $this->apiSecret);
    }
}
