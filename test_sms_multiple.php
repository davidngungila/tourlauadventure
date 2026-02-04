<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== SMS Testing Script ===\n\n";

// Ensure provider is configured
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

echo "Provider configured: {$provider->name}\n";
echo "Bearer Token: " . substr($provider->sms_bearer_token, 0, 20) . "...\n";
echo "URL: {$provider->sms_url}\n\n";

$phone = '0622239304';
$service = new \App\Services\NotificationService();

$tests = [
    "Test 1: POST method with Bearer token",
    "Test 2: Second attempt",
    "Test 3: Third attempt",
    "Test 4: Fourth attempt",
    "Test 5: Final attempt"
];

$successCount = 0;
$failCount = 0;

foreach ($tests as $index => $message) {
    echo "--- {$message} ---\n";
    echo "Sending to: {$phone}\n";
    
    try {
        $result = $service->sendSMS($phone, $message);
        
        if ($result) {
            echo "✓ SUCCESS!\n";
            $successCount++;
        } else {
            echo "✗ FAILED (returned false)\n";
            $failCount++;
        }
    } catch (\Exception $e) {
        echo "✗ ERROR: " . $e->getMessage() . "\n";
        $failCount++;
    }
    
    echo "\n";
    sleep(2); // Wait 2 seconds between tests
}

echo "=== Results ===\n";
echo "Successful: {$successCount}\n";
echo "Failed: {$failCount}\n";
echo "Total: " . count($tests) . "\n";

if ($successCount > 0) {
    echo "\n✓ SMS service is working!\n";
} else {
    echo "\n✗ All tests failed. Check logs for details.\n";
}



