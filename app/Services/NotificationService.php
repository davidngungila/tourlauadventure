<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\SystemSetting;
use App\Models\NotificationProvider;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Mail\Message;

class NotificationService
{
    protected $smsUsername;
    protected $smsPassword;
    protected $smsBearerToken;
    protected $smsFrom;
    protected $smsUrl;
    protected $smsProvider;
    protected $emailProvider;

    public function __construct()
    {
        try {
            // Get primary providers from database
            $this->smsProvider = class_exists(NotificationProvider::class) 
                ? NotificationProvider::getPrimary('sms') 
                : null;
            $this->emailProvider = class_exists(NotificationProvider::class) 
                ? NotificationProvider::getPrimary('email') 
                : null;
            
            // Fallback to SystemSetting if no provider found
            if ($this->smsProvider) {
                $this->smsUsername = $this->smsProvider->sms_username;
                $this->smsPassword = $this->smsProvider->sms_password;
                $this->smsBearerToken = $this->smsProvider->sms_bearer_token;
                $this->smsFrom = $this->smsProvider->sms_from;
                $this->smsUrl = $this->smsProvider->sms_url ?: 'https://messaging-service.co.tz/api/sms/v2/text/single';
            } else {
                // Fallback to SystemSetting, then env
                $this->smsUsername = class_exists(SystemSetting::class) 
                    ? (SystemSetting::getValue('sms_username') ?: env('SMS_USERNAME', ''))
                    : env('SMS_USERNAME', '');
                $this->smsPassword = class_exists(SystemSetting::class) 
                    ? (SystemSetting::getValue('sms_password') ?: env('SMS_PASSWORD', ''))
                    : env('SMS_PASSWORD', '');
                $this->smsBearerToken = class_exists(SystemSetting::class) 
                    ? (SystemSetting::getValue('sms_bearer_token') ?: env('SMS_BEARER_TOKEN', 'cedcce9becad866f59beac1fd5a235bc'))
                    : env('SMS_BEARER_TOKEN', 'cedcce9becad866f59beac1fd5a235bc');
                $this->smsFrom = class_exists(SystemSetting::class) 
                    ? (SystemSetting::getValue('sms_from') ?: env('SMS_FROM', 'LAUPARADISE'))
                    : env('SMS_FROM', 'LAUPARADISE');
                $this->smsUrl = class_exists(SystemSetting::class) 
                    ? (SystemSetting::getValue('sms_url') ?: env('SMS_URL', 'https://messaging-service.co.tz/api/sms/v2/text/single'))
                    : env('SMS_URL', 'https://messaging-service.co.tz/api/sms/v2/text/single');
            }
        } catch (\Exception $e) {
            // Table might not exist yet, use fallback
            Log::warning('NotificationProvider table not available, using env fallback: ' . $e->getMessage());
            $this->smsUsername = env('SMS_USERNAME', 'lauparadise');
            $this->smsPassword = env('SMS_PASSWORD', 'Lau123.@');
            $this->smsBearerToken = env('SMS_BEARER_TOKEN', 'cedcce9becad866f59beac1fd5a235bc');
            $this->smsFrom = env('SMS_FROM', 'LAUPARADISE');
            $this->smsUrl = env('SMS_URL', 'https://messaging-service.co.tz/api/sms/v2/text/single');
        }
    }

    /**
     * Send notification to user(s) via all channels
     * 
     * @param array|int $userIds User ID(s) to notify
     * @param string $message Message to send
     * @param string|null $link Optional link for in-app notification
     * @param string|null $subject Optional email subject
     * @param array $data Additional data for email template
     */
    public function notify($userIds, string $message, ?string $link = null, ?string $subject = null, array $data = [])
    {
        if (!is_array($userIds)) {
            $userIds = [$userIds];
        }

        // Check if SMS should be skipped (from $data array)
        $skipSMS = isset($data['skip_sms']) && $data['skip_sms'] === true;
        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            // 1. In-App Notification
            $this->sendInAppNotification($user->id, $message, $link);

            // 2. SMS Notification - check both mobile and phone fields (skip if requested)
            if (!$skipSMS) {
                $phone = $user->mobile ?? $user->phone ?? null;
                if ($phone) {
                    try {
                        $smsResult = $this->sendSMS($phone, $message);
                        if ($smsResult && class_exists(\App\Services\ActivityLogService::class)) {
                            // Log SMS sent activity
                            \App\Services\ActivityLogService::logSMSSent($phone, $message, Auth::id(), $user->id, [
                                'notification_type' => 'multi_channel',
                                'link' => $link,
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::warning('SMS sending failed in notify method', [
                            'user_id' => $user->id,
                            'phone' => $phone,
                            'error' => $e->getMessage()
                        ]);
                        // Continue with other notifications even if SMS fails
                    }
                }
            }

            // 3. Email Notification
            if ($user->email) {
                $emailSubject = $subject ?? 'Tour Booking Notification';
                $emailResult = $this->sendEmail($user->email, $emailSubject, $message, $data);
                if ($emailResult && class_exists(\App\Services\ActivityLogService::class)) {
                    // Log email sent activity
                    \App\Services\ActivityLogService::logEmailSent($user->email, $emailSubject, Auth::id(), $user->id, [
                        'notification_type' => 'multi_channel',
                        'link' => $link,
                    ]);
                }
            }
        }

        // Log notification sent activity
        if (class_exists(\App\Services\ActivityLogService::class)) {
            \App\Services\ActivityLogService::logNotificationSent($userIds, $message, $link, Auth::id(), [
                'subject' => $subject,
                'notification_count' => count($users),
            ]);
        }

        // Broadcast for real-time toast notifications (if using Laravel Echo/WebSockets)
        $this->broadcastNotification($userIds, $message, $link);
    }

    /**
     * Send notification to phone number directly (for non-user bookings)
     * 
     * @param string $phoneNumber Phone number to send SMS to
     * @param string $message Message to send
     * @param string|null $email Optional email to send notification to
     * @param string|null $subject Optional email subject
     * @param array $data Additional data
     */
    public function notifyPhone(string $phoneNumber, string $message, ?string $email = null, ?string $subject = null, array $data = [])
    {
        // Send SMS
        $skipSMS = isset($data['skip_sms']) && $data['skip_sms'] === true;
        if (!$skipSMS && $phoneNumber) {
            try {
                $this->sendSMS($phoneNumber, $message);
            } catch (\Exception $e) {
                Log::warning('SMS sending failed in notifyPhone method', [
                    'phone' => $phoneNumber,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Send Email if provided
        if ($email) {
            $emailSubject = $subject ?? 'Tour Booking Notification';
            $this->sendEmail($email, $emailSubject, $message, $data);
        }
    }

    /**
     * Send in-app notification
     */
    protected function sendInAppNotification(int $userId, string $message, ?string $link = null)
    {
        try {
            if (class_exists(Notification::class)) {
                Notification::create([
                    'user_id' => $userId,
                    'message' => $message,
                    'link' => $link,
                    'is_read' => false,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to create in-app notification: ' . $e->getMessage());
        }
    }

    /**
     * Send SMS using Bearer token authentication (v2 API)
     * Supports both POST (Bearer token) and GET (token in URL) methods
     */
    public function sendSMS(string $phoneNumber, string $message, ?NotificationProvider $provider = null)
    {
        try {
            // Use provided provider or fallback to default
            $provider = $provider ?? $this->smsProvider;
            
            if ($provider) {
                $smsBearerToken = $provider->sms_bearer_token;
                $smsFrom = $provider->sms_from;
                $smsUrl = $provider->sms_url ?: 'https://messaging-service.co.tz/api/sms/v2/text/single';
            } else {
                $smsBearerToken = $this->smsBearerToken;
                $smsFrom = $this->smsFrom;
                $smsUrl = $this->smsUrl ?: 'https://messaging-service.co.tz/api/sms/v2/text/single';
            }

            // Check if bearer token is available
            if (empty($smsBearerToken)) {
                $errorMsg = 'SMS sending failed: Bearer token not configured';
                Log::error($errorMsg, [
                    'phone' => $phoneNumber,
                    'provider' => $provider ? $provider->name : 'none',
                    'provider_id' => $provider ? $provider->id : null,
                    'fallback_token_set' => !empty($this->smsBearerToken)
                ]);
                throw new \Exception($errorMsg . '. Please configure SMS Bearer Token in Notification Provider or environment variables.');
            }

            // Validate and format phone number
            $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            
            // Format to 255XXXXXXXXX if needed
            if (!str_starts_with($phoneNumber, '255')) {
                $phoneNumber = '255' . ltrim($phoneNumber, '0');
            }
            
            // Validate format (255 followed by 9 digits)
            if (!preg_match('/^255[0-9]{9}$/', $phoneNumber)) {
                Log::error('SMS sending failed: Invalid phone number format', [
                    'phone' => $phoneNumber,
                    'expected_format' => '255XXXXXXXXX'
                ]);
                return false;
            }

            // Determine if URL contains /link/ (GET method) or /api/ (POST method)
            $useGetMethod = strpos($smsUrl, '/link/') !== false;
            
            // Initialize cURL
            $curl = curl_init();
            
            if ($useGetMethod) {
                // GET method with token in URL
                $text = urlencode($message);
                $url = $smsUrl . 
                       '?token=' . urlencode($smsBearerToken) . 
                       '&from=' . urlencode($smsFrom ?: 'LAUPARADISE') . 
                       '&to=' . $phoneNumber . 
                       '&text=' . $text;
                
                Log::info('Sending SMS (GET method)', [
                    'phone' => $phoneNumber,
                    'from' => $smsFrom,
                    'url' => $url
                ]);
                
                curl_setopt_array($curl, [
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_USERAGENT => 'Tour-Booking-SMS-Client/2.0'
                ]);
            } else {
                // POST method with Bearer token in header
                $body = json_encode([
                    'from' => $smsFrom ?: 'LAUPARADISE',
                    'to' => $phoneNumber,
                    'text' => $message,
                    'flash' => 0,
                    'reference' => 'tour_' . time() . '_' . rand(1000, 9999)
                ]);

                Log::info('Sending SMS (POST method)', [
                    'phone' => $phoneNumber,
                    'from' => $smsFrom,
                    'url' => $smsUrl,
                    'bearer_token_set' => !empty($smsBearerToken),
                    'bearer_token_preview' => $smsBearerToken ? substr($smsBearerToken, 0, 10) . '...' : 'NOT SET',
                    'message_preview' => substr($message, 0, 50) . (strlen($message) > 50 ? '...' : '')
                ]);
                
                curl_setopt_array($curl, [
                    CURLOPT_URL => $smsUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $body,
                    CURLOPT_HTTPHEADER => [
                        'Authorization: Bearer ' . $smsBearerToken,
                        'Content-Type: application/json',
                        'Accept: application/json'
                    ],
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_USERAGENT => 'Tour-Booking-SMS-Client/2.0'
                ]);
            }

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curlError = curl_error($curl);
            $curlErrno = curl_errno($curl);

            if ($curlErrno) {
                $errorMsg = "cURL Error ({$curlErrno}): {$curlError}";
                Log::error('SMS cURL Error', [
                    'error_code' => $curlErrno,
                    'error_message' => $curlError,
                    'phone' => $phoneNumber
                ]);
                curl_close($curl);
                throw new \Exception($errorMsg);
            }

            curl_close($curl);

            // Log response
            Log::info('SMS API Response', [
                'http_code' => $httpCode,
                'response' => $response,
                'phone' => $phoneNumber,
                'method' => $useGetMethod ? 'GET' : 'POST'
            ]);

            // Check response - Accept any 2xx status as success
            if ($httpCode >= 200 && $httpCode < 300) {
                $responseData = json_decode($response, true);
                
                // Check for explicit error in response
                if ($responseData && isset($responseData['error'])) {
                    $errorMsg = 'SMS API error: ' . ($responseData['error'] ?? 'Unknown error');
                    if (isset($responseData['message'])) {
                        $errorMsg .= ' - ' . $responseData['message'];
                    }
                    Log::error('SMS API returned error in response', [
                        'phone' => $phoneNumber,
                        'response' => $response,
                        'error' => $errorMsg
                    ]);
                    throw new \Exception($errorMsg);
                }
                
                // Check for rejected status in messages array (GET method response format)
                if ($responseData && isset($responseData['messages']) && is_array($responseData['messages'])) {
                    foreach ($responseData['messages'] as $msg) {
                        if (isset($msg['status']['groupName']) && $msg['status']['groupName'] === 'REJECTED') {
                            $errorMsg = 'SMS rejected: ' . ($msg['status']['description'] ?? 'Unknown reason');
                            Log::error('SMS rejected by provider', [
                                'phone' => $phoneNumber,
                                'response' => $response,
                                'error' => $errorMsg
                            ]);
                            throw new \Exception($errorMsg);
                        }
                    }
                }
                
                // Success - any 2xx response without error is considered success
                Log::info('SMS sent successfully', [
                    'phone' => $phoneNumber,
                    'http_code' => $httpCode,
                    'response' => $response
                ]);
                
                // Log SMS activity
                try {
                    $userId = Auth::id();
                    $user = User::where('mobile', $phoneNumber)
                        ->orWhere('phone', $phoneNumber)
                        ->first();
                    if (class_exists(\App\Services\ActivityLogService::class)) {
                        \App\Services\ActivityLogService::logSMSSent($phoneNumber, $message, $userId, $user?->id, [
                            'provider' => $provider ? $provider->name : 'default',
                            'sms_from' => $smsFrom,
                            'response_code' => $httpCode,
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to log SMS activity', ['error' => $e->getMessage()]);
                }
                
                return true;
            } else {
                $errorMsg = "SMS failed with HTTP code {$httpCode}";
                if ($response) {
                    $responseData = json_decode($response, true);
                    if ($responseData && isset($responseData['error'])) {
                        $errorMsg .= ': ' . $responseData['error'];
                    } elseif ($responseData && isset($responseData['message'])) {
                        $errorMsg .= ': ' . $responseData['message'];
                    } else {
                        $errorMsg .= ': ' . substr($response, 0, 200);
                    }
                }
                Log::error('SMS failed', [
                    'http_code' => $httpCode,
                    'response' => $response,
                    'phone' => $phoneNumber,
                    'error' => $errorMsg
                ]);
                throw new \Exception($errorMsg);
            }
        } catch (\Exception $e) {
            Log::error('SMS sending exception', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'phone' => $phoneNumber ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Send email notification
     */
    protected function sendEmail(string $email, string $subject, string $message, array $data = [], ?NotificationProvider $provider = null)
    {
        try {
            // Use provided provider or fallback to default
            $provider = $provider ?? $this->emailProvider;
            
            if ($provider) {
                // Update mail config from provider
                config([
                    'mail.default' => $provider->mailer_type ?? 'smtp',
                    'mail.mailers.smtp.host' => $provider->mail_host ?? '',
                    'mail.mailers.smtp.port' => $provider->mail_port ?? 587,
                    'mail.mailers.smtp.username' => $provider->mail_username ?? '',
                    'mail.mailers.smtp.password' => $provider->mail_password ?? '',
                    'mail.mailers.smtp.encryption' => $provider->mail_encryption ?? 'tls',
                    'mail.from.address' => $provider->mail_from_address ?? '',
                    'mail.from.name' => $provider->mail_from_name ?? 'Tour Booking',
                ]);
            } else {
                // Fallback to SystemSetting
                $this->updateMailConfigFromSettings();
            }
            
            // Set stream context to disable SSL verification (for self-signed certificates)
            stream_context_set_default([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]);
            
            Mail::send('emails.notification', [
                'emailMessage' => $message,
                'data' => $data,
            ], function (Message $mail) use ($email, $subject) {
                $mail->to($email)
                     ->subject($subject);
            });
            
            // Log email activity if not already logged (for direct email calls)
            try {
                $userId = Auth::id();
                $user = User::where('email', $email)->first();
                if (class_exists(\App\Services\ActivityLogService::class)) {
                    \App\Services\ActivityLogService::logEmailSent($email, $subject, $userId, $user?->id, [
                        'provider' => $provider ? $provider->name : 'default',
                    ]);
                }
            } catch (\Exception $e) {
                // Don't fail email sending if activity log fails
                Log::warning('Failed to log email activity', ['error' => $e->getMessage()]);
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Email sending error: ' . $e->getMessage(), [
                'email' => $email,
                'provider_id' => $provider ? $provider->id : null
            ]);
            return false;
        }
    }
    
    /**
     * Update mail configuration from SystemSetting (fallback)
     */
    protected function updateMailConfigFromSettings()
    {
        if (!class_exists(SystemSetting::class)) {
            return;
        }

        $mailer = SystemSetting::getValue('mail_mailer', config('mail.default', 'smtp'));
        $host = SystemSetting::getValue('mail_host', config('mail.mailers.smtp.host', ''));
        $port = SystemSetting::getValue('mail_port', config('mail.mailers.smtp.port', 587));
        $username = SystemSetting::getValue('mail_username', config('mail.mailers.smtp.username', ''));
        $password = SystemSetting::getValue('mail_password', config('mail.mailers.smtp.password', ''));
        $encryption = SystemSetting::getValue('mail_encryption', config('mail.mailers.smtp.encryption', 'tls'));
        $fromAddress = SystemSetting::getValue('mail_from_address', config('mail.from.address', ''));
        $fromName = SystemSetting::getValue('mail_from_name', config('mail.from.name', 'Tour Booking'));

        config([
            'mail.default' => $mailer,
            'mail.mailers.smtp.host' => $host,
            'mail.mailers.smtp.port' => $port,
            'mail.mailers.smtp.username' => $username,
            'mail.mailers.smtp.password' => $password,
            'mail.mailers.smtp.encryption' => $encryption,
            'mail.from.address' => $fromAddress,
            'mail.from.name' => $fromName,
        ]);
    }

    /**
     * Broadcast notification for real-time updates (toast notifications)
     */
    protected function broadcastNotification(array $userIds, string $message, ?string $link = null)
    {
        // This will be handled by Laravel Broadcasting/WebSockets if configured
        // For now, we'll log it. Frontend can poll for new notifications or use Server-Sent Events
        try {
            if (class_exists(\App\Events\NotificationSent::class)) {
                event(new \App\Events\NotificationSent($userIds, $message, $link));
            }
        } catch (\Exception $e) {
            // If event broadcasting is not set up, silently fail
            Log::debug('Broadcasting not configured: ' . $e->getMessage());
        }
    }

    /**
     * Notify users by role
     */
    public function notifyByRole(array $roleNames, string $message, ?string $link = null, ?string $subject = null, array $data = [])
    {
        $userIds = User::whereHas('roles', function($query) use ($roleNames) {
            $query->whereIn('slug', $roleNames);
        })->pluck('id')->toArray();

        if (!empty($userIds)) {
            $this->notify($userIds, $message, $link, $subject, $data);
        }
    }

    /**
     * Notify travel consultants about new booking
     */
    public function notifyTravelConsultants(string $message, ?string $link = null, ?string $subject = null, array $data = [])
    {
        $this->notifyByRole(['travel-consultant'], $message, $link, $subject, $data);
    }

    /**
     * Notify reservations officers about new booking
     */
    public function notifyReservationsOfficers(string $message, ?string $link = null, ?string $subject = null, array $data = [])
    {
        $this->notifyByRole(['reservations-officer'], $message, $link, $subject, $data);
    }
}






