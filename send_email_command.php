<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== SENDING EMAIL VIA ARTISAN COMMAND ===\n\n";

try {
    // Get email configuration from database
    $provider = DB::table('notification_providers')
        ->where('type', 'email')
        ->where('is_primary', true)
        ->where('is_active', true)
        ->first();
    
    if (!$provider) {
        echo "❌ No active email provider found in database\n";
        exit;
    }
    
    echo "✅ Using provider: {$provider->name}\n";
    
    // Configure mail from database
    config([
        'mail.default' => 'smtp',
        'mail.mailers.smtp.host' => $provider->mail_host,
        'mail.mailers.smtp.port' => $provider->mail_port,
        'mail.mailers.smtp.username' => $provider->mail_username,
        'mail.mailers.smtp.password' => $provider->mail_password,
        'mail.mailers.smtp.encryption' => $provider->mail_encryption,
        'mail.from.address' => $provider->mail_from_address,
        'mail.from.name' => $provider->mail_from_name,
    ]);
    
    // Send email
    $recipient = 'ecolishe@gmail.com';
    $subject = 'Artisan Command Test - Lau Paradise Adventures';
    $message = "This email was sent using Laravel artisan command with database configuration.

Provider: {$provider->name}
SMTP Host: {$provider->mail_host}
SMTP Port: {$provider->mail_port}
From: {$provider->mail_from_address}
Time: " . date('Y-m-d H:i:s') . "

✅ SUCCESS! Database-driven email system working perfectly!

Please check your inbox for this confirmation email.

Sent via: PHP Artisan Command
Configuration Source: Database (notification_providers table)";

    Mail::raw($message, function($mail) use ($recipient, $subject, $provider) {
        $mail->to($recipient)
             ->subject($subject)
             ->from($provider->mail_from_address, $provider->mail_from_name)
             ->replyTo($provider->mail_from_address);
    });
    
    echo "✅ Email sent successfully via artisan command!\n";
    echo "   To: $recipient\n";
    echo "   Subject: $subject\n";
    echo "   Time: " . date('Y-m-d H:i:s') . "\n\n";
    
    // Update provider status
    DB::table('notification_providers')
        ->where('id', $provider->id)
        ->update([
            'connection_status' => 'connected',
            'last_tested_at' => now(),
            'last_test_result' => 'Success: Email sent via artisan command to ' . $recipient
        ]);
    
    echo "📊 Provider status updated in database\n\n";
    echo "🎉 EMAIL SENT SUCCESSFULLY VIA ARTISAN COMMAND! 🎉\n";
    echo "Check your inbox for: '$subject'\n";
    
} catch (Exception $e) {
    echo "❌ Error sending email via artisan command: " . $e->getMessage() . "\n";
}

echo "\nProcess completed at: " . date('Y-m-d H:i:s') . "\n";
