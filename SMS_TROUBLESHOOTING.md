# SMS Troubleshooting Guide

## Current Configuration
- **Sender ID**: LAUPARADISE
- **Bearer Token**: 737b713d636ffaaebb67eee0e7165ad9
- **API URL**: https://messaging-service.co.tz/api/sms/v2/text/single
- **Username**: lauparadise
- **Password**: Lau123.@

## Quick Fix Commands

### 1. Ensure Provider Exists in Database
```bash
php artisan tinker
```
Then run:
```php
\App\Models\NotificationProvider::updateOrCreate(
    ['type' => 'sms', 'is_primary' => true],
    [
        'name' => 'Lau Paradise SMS Provider',
        'is_active' => true,
        'sms_bearer_token' => '737b713d636ffaaebb67eee0e7165ad9',
        'sms_from' => 'LAUPARADISE',
        'sms_url' => 'https://messaging-service.co.tz/api/sms/v2/text/single',
        'sms_username' => 'lauparadise',
        'sms_password' => 'Lau123.@',
        'sms_method' => 'post',
    ]
);
```

### 2. Test SMS
```bash
php artisan sms:test 0622239304
```

### 3. Check Logs
```bash
Get-Content storage/logs/laravel.log -Tail 50
```

## Common Issues

### Issue: "Bearer token not configured"
**Solution**: Run the seeder or manually create the provider:
```bash
php artisan db:seed --class=SmsProviderSeeder
```

### Issue: SMS fails with HTTP error
**Check**:
1. Bearer token is correct
2. Phone number format (should be 255XXXXXXXXX)
3. API endpoint is accessible
4. Check logs for detailed error message

### Issue: Provider not loading
**Solution**: Clear cache and config:
```bash
php artisan config:clear
php artisan cache:clear
```

## Testing

Run the debug script:
```bash
php test_sms_debug.php
```

Or use artisan command:
```bash
php artisan sms:test 0622239304 --message="Your test message"
```

## API Request Format

The service sends:
```json
{
    "from": "LAUPARADISE",
    "to": "255622239304",
    "text": "Your message",
    "flash": 0,
    "reference": "tour_1234567890_5678"
}
```

With headers:
- `Authorization: Bearer 737b713d636ffaaebb67eee0e7165ad9`
- `Content-Type: application/json`
- `Accept: application/json`



