<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;

class TestSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:test {phone?} {--message=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test SMS sending functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $phone = $this->argument('phone') ?: '0622239304';
        $message = $this->option('message') ?: 'Hello! This is a test SMS from Tour Booking System.';

        $this->info("=== SMS Test ===");
        $this->info("Phone: {$phone}");
        $this->info("Message: {$message}");
        $this->newLine();

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

        $this->info("Provider: {$provider->name}");
        $this->info("Bearer Token: " . substr($provider->sms_bearer_token, 0, 20) . "...");
        $this->info("URL: {$provider->sms_url}");
        $this->newLine();

        $notificationService = new NotificationService();
        
        $this->info("Sending SMS...");
        
        try {
            $result = $notificationService->sendSMS($phone, $message);

            if ($result) {
                $this->info("✓✓✓ SUCCESS! SMS sent successfully! ✓✓✓");
                return 0;
            } else {
                $this->error("✗ SMS sending failed (returned false). Check logs for details.");
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("✗ ERROR: " . $e->getMessage());
            $this->warn("Check logs: storage/logs/laravel.log");
            return 1;
        }
    }
}
