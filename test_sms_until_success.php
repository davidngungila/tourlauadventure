<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== SMS Testing Until Success ===\n\n";

// Ensure provider is configured correctly
$provider = \App\Models\NotificationProvider::updateOrCreate(
    ['type' => 'sms', 'is_primary' => true],
    [
        'name' => 'Lau Paradise SMS Provider',
        'is_active' => true,
        'sms_bearer_token' => 'cedcce9becad866f59beac1fd5a235bc',
        'sms_from' => 'LAUPARADISE',
        'sms_url' => 'https://messaging-service.co.tz/api/sms/v2/text/single',
        'sms_username' => 'lauparadise',
        'sms_password' => 'Lau123.@',
        'sms_method' => 'post',
    ]
);

echo "Provider: {$provider->name}\n";
echo "Bearer Token: " . substr($provider->sms_bearer_token, 0, 20) . "...\n";
echo "URL: {$provider->sms_url}\n";
echo "From: {$provider->sms_from}\n\n";

$phone = '0622239304';
$service = new \App\Services\NotificationService();

$attempt = 1;
$maxAttempts = 10;
$success = false;

while (!$success && $attempt <= $maxAttempts) {
    $message = "Test SMS #{$attempt} from Lau Paradise Adventures - " . date('H:i:s');
    
    echo "--- Attempt #{$attempt} ---\n";
    echo "Time: " . date('Y-m-d H:i:s') . "\n";
    echo "Phone: {$phone}\n";
    echo "Message: {$message}\n";
    echo "Sending...\n";
    
    try {
        $result = $service->sendSMS($phone, $message);
        
        if ($result === true) {
            echo "✓✓✓ SUCCESS! SMS sent successfully! ✓✓✓\n";
            $success = true;
        } else {
            echo "✗ Failed (returned false)\n";
        }
    } catch (\Exception $e) {
        $errorMsg = $e->getMessage();
        echo "✗ Error: {$errorMsg}\n";
        
        // Check if it's a credit issue
        if (stripos($errorMsg, 'credit') !== false || stripos($errorMsg, 'REJECTED') !== false) {
            echo "⚠ Note: This appears to be a credit/account issue, not a code issue.\n";
        }
    }
    
    echo "\n";
    
    if (!$success && $attempt < $maxAttempts) {
        echo "Waiting 3 seconds before next attempt...\n\n";
        sleep(3);
    }
    
    $attempt++;
}

if ($success) {
    echo "=== TEST COMPLETE ===\n";
    echo "✓ SMS service is working correctly!\n";
    echo "Successful attempt: #" . ($attempt - 1) . "\n";
} else {
    echo "=== TEST COMPLETE ===\n";
    echo "✗ All {$maxAttempts} attempts failed.\n";
    echo "Please check:\n";
    echo "1. Bearer token is correct\n";
    echo "2. Account has sufficient credits\n";
    echo "3. Phone number format is correct\n";
    echo "4. Check logs: storage/logs/laravel.log\n";
}



