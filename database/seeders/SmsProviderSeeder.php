<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationProvider;

class SmsProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update the primary SMS provider
        $provider = NotificationProvider::updateOrCreate(
            [
                'type' => 'sms',
                'is_primary' => true,
            ],
            [
                'name' => 'Lau Paradise SMS Provider',
                'type' => 'sms',
                'is_primary' => true,
                'is_active' => true,
                'sms_username' => 'lauparadise',
                'sms_password' => 'Lau123.@',
                'sms_bearer_token' => 'cedcce9becad866f59beac1fd5a235bc',
                'sms_from' => 'LAUPARADISE',
                'sms_url' => 'https://messaging-service.co.tz/api/sms/v2/text/single',
                'sms_method' => 'post',
                'connection_status' => 'connected',
                'priority' => 1,
            ]
        );

        $this->command->info('SMS Provider configured successfully!');
        $this->command->info('Sender ID: LAUPARADISE');
        $this->command->info('API Token: 737b713d636ffaaebb67eee0e7165ad9');
    }
}

