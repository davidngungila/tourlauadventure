<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CloudinaryAccount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'cloud_name',
        'api_key',
        'api_secret',
        'cloudinary_url',
        'is_active',
        'is_default',
        'description',
        'settings',
        'last_connection_test',
        'connection_status',
        'connection_error',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'connection_status' => 'boolean',
        'settings' => 'array',
        'last_connection_test' => 'datetime',
    ];

    /**
     * Get the default account
     */
    public static function getDefault()
    {
        return static::where('is_active', true)
            ->where('is_default', true)
            ->first() ?? static::where('is_active', true)->first();
    }

    /**
     * Get active accounts
     */
    public static function getActive()
    {
        return static::where('is_active', true)->orderBy('is_default', 'desc')->get();
    }

    /**
     * Get Cloudinary URL from credentials
     */
    public function getCloudinaryUrlAttribute($value)
    {
        if ($value) {
            return $value;
        }

        // Build URL from credentials
        if ($this->cloud_name && $this->api_key && $this->api_secret) {
            return "cloudinary://{$this->api_key}:{$this->api_secret}@{$this->cloud_name}";
        }

        return null;
    }

    /**
     * Test connection to Cloudinary
     */
    public function testConnection()
    {
        try {
            $cloudName = $this->cloud_name;
            $apiKey = $this->api_key;
            $apiSecret = $this->api_secret;

            if (!$cloudName || !$apiKey || !$apiSecret) {
                throw new \Exception('Missing credentials');
            }

            $baseUrl = "https://api.cloudinary.com/v1_1/{$cloudName}";
            $timestamp = time();
            $params = ['timestamp' => $timestamp];
            $signature = $this->generateSignature($params, $timestamp);

            $response = \Illuminate\Support\Facades\Http::withBasicAuth($apiKey, $apiSecret)
                ->get("{$baseUrl}/resources/image/upload", [
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                    'max_results' => 1,
                ]);

            $this->last_connection_test = now();
            $this->connection_status = $response->successful();
            $this->connection_error = $response->successful() ? null : ($response->json()['error']['message'] ?? $response->body());
            $this->save();

            return [
                'success' => $response->successful(),
                'message' => $response->successful() ? 'Connection successful' : ($response->json()['error']['message'] ?? 'Connection failed'),
            ];
        } catch (\Exception $e) {
            $this->last_connection_test = now();
            $this->connection_status = false;
            $this->connection_error = $e->getMessage();
            $this->save();

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
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
        return sha1($signatureString . $this->api_secret);
    }
}
