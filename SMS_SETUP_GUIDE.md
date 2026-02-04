# SMS Setup Guide

## Updated SMS API Configuration

The SMS service has been updated to use the new Bearer token authentication with the v2 API endpoint.

### API Details:
- **Endpoint**: `https://messaging-service.co.tz/api/sms/v2/text/single`
- **Method**: POST
- **Authentication**: Bearer Token
- **Headers**: 
  - `Authorization: Bearer {token}`
  - `Content-Type: application/json`
  - `Accept: application/json`

### Configuration Options:

#### Option 1: Using Notification Provider (Recommended)
1. Go to Admin Dashboard → Notification Providers
2. Create or edit an SMS provider
3. Set the following fields:
   - **Name**: e.g., "Tanzania SMS Provider"
   - **Type**: SMS
   - **SMS Bearer Token**: `cedcce9becad866f59beac1fd5a235bc` (your actual token)
   - **SMS From**: `TANZANIATIP` (or your sender ID)
   - **SMS URL**: `https://messaging-service.co.tz/api/sms/v2/text/single`
   - **Is Primary**: Yes
   - **Is Active**: Yes

#### Option 2: Using Environment Variables
Add to your `.env` file:
```env
SMS_BEARER_TOKEN=cedcce9becad866f59beac1fd5a235bc
SMS_FROM=TANZANIATIP
SMS_URL=https://messaging-service.co.tz/api/sms/v2/text/single
```

#### Option 3: Using System Settings
Set the following system settings:
- `sms_bearer_token`: Your bearer token
- `sms_from`: Your sender ID
- `sms_url`: The API endpoint

### Testing SMS

Run the test command:
```bash
php artisan sms:test 0622239304 --message="Test message"
```

Or test with a specific phone number:
```bash
php artisan sms:test 255622239304
```

### Phone Number Format
- Phone numbers are automatically formatted to `255XXXXXXXXX` format
- Input can be: `0622239304`, `255622239304`, or `+255622239304`
- All will be converted to: `255622239304`

### Request Body Format
```json
{
    "from": "TANZANIATIP",
    "to": "255622239304",
    "text": "Your message here",
    "flash": 0,
    "reference": "tour_1234567890_5678"
}
```

### Response Handling
- HTTP 200/201: Success
- Checks for `success: true` in JSON response
- Logs all requests and responses for debugging

### Migration
Run the migration to add the bearer token field:
```bash
php artisan migrate
```

This adds the `sms_bearer_token` column to the `notification_providers` table.



