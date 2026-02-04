<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - Lau Paradise Adventures</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #1a4d3e 0%, #2d7a5f 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .success-badge {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        .booking-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .booking-details h3 {
            margin-top: 0;
            color: #1a4d3e;
            border-bottom: 2px solid #1a4d3e;
            padding-bottom: 10px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #495057;
        }
        .detail-value {
            color: #1a4d3e;
        }
        .cta-button {
            display: inline-block;
            background-color: #1a4d3e;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            border-top: 1px solid #dee2e6;
        }
        .contact-info {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
        }
        .contact-info p {
            margin: 5px 0;
        }
        .contact-info a {
            color: #1a4d3e;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Lau Paradise Adventures</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Booking Confirmation</p>
        </div>
        
        <div class="content">
            @if($booking->status === 'confirmed')
            <div class="success-badge">
                <strong>✓ Your booking has been confirmed!</strong>
            </div>
            @else
            <div style="background-color: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; text-align: center; margin-bottom: 20px; border-left: 4px solid #ffc107;">
                <strong>📧 Payment Link Sent - Please Complete Payment</strong>
            </div>
            @endif
            
            <p>Dear {{ $booking->customer_name }},</p>
            
            <p>Thank you for choosing Lau Paradise Adventures! We're excited to be part of your Tanzania adventure.</p>
            
            @if($booking->status === 'pending_payment')
            <p><strong>Your booking has been received and is pending payment confirmation.</strong> Please use the secure payment link below to complete your payment and confirm your booking.</p>
            @endif
            
            <div class="booking-details">
                <h3>Booking Details</h3>
                <div class="detail-row">
                    <span class="detail-label">Booking Reference:</span>
                    <span class="detail-value"><strong>{{ $booking->booking_reference }}</strong></span>
                </div>
                @if($booking->tour)
                <div class="detail-row">
                    <span class="detail-label">Tour:</span>
                    <span class="detail-value">{{ $booking->tour->name }}</span>
                </div>
                @endif
                @if($booking->departure_date)
                <div class="detail-row">
                    <span class="detail-label">Departure Date:</span>
                    <span class="detail-value">{{ $booking->departure_date->format('F j, Y') }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Number of Travelers:</span>
                    <span class="detail-value">{{ $booking->travelers }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Amount:</span>
                    <span class="detail-value"><strong>${{ number_format($booking->total_price, 2) }}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        @if($booking->status === 'confirmed')
                            <span style="color: #28a745; font-weight: bold;">Confirmed</span>
                        @elseif($booking->status === 'pending_payment')
                            <span style="color: #ffc107; font-weight: bold;">Pending Payment</span>
                        @else
                            <span style="color: #6c757d;">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
                        @endif
                    </span>
                </div>
            </div>
            
            @if($booking->status === 'pending_payment')
            <div style="background-color: #fff3cd; padding: 20px; border-radius: 5px; border-left: 4px solid #ffc107; margin: 20px 0;">
                <p style="margin: 0 0 15px 0;">
                    <strong style="font-size: 16px;">⚠️ Payment Required to Confirm Your Booking</strong>
                </p>
                <p style="margin: 0;">
                    Your booking has been received, but it will only be confirmed after you complete the payment. Please use the secure payment link below to complete your payment.
                </p>
            </div>
            
            @if(isset($paymentLinkUrl) && $paymentLinkUrl)
            <div style="text-align: center; margin: 30px 0; padding: 25px; background-color: #f8f9fa; border-radius: 8px; border: 2px solid #1a4d3e;">
                <p style="margin: 0 0 20px 0; font-size: 16px; font-weight: bold; color: #1a4d3e;">
                    Complete Your Payment Now
                </p>
                <a href="{{ $paymentLinkUrl }}" class="cta-button" style="display: inline-block; background-color: #28a745; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; font-size: 16px; font-weight: bold; margin: 10px 0;">
                    💳 Pay Securely via Stripe
                </a>
                <p style="margin: 15px 0 0 0; font-size: 12px; color: #6c757d;">
                    Click the button above to complete your payment securely. Your booking will be confirmed immediately after payment.
                </p>
            </div>
            @else
            <div style="text-align: center; margin: 30px 0;">
                <p style="color: #dc3545; font-weight: bold;">Payment link will be sent separately. Please check your email or contact us if you haven't received it.</p>
            </div>
            @endif
            @endif
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('booking.confirmation', $booking->id) }}" class="cta-button" style="background-color: #6c757d;">View Booking Details</a>
            </div>
            
            <p>If you have any questions or need to make changes to your booking, please don't hesitate to contact us.</p>
            
            <div class="contact-info">
                <p><strong>Contact Us:</strong></p>
                <p><strong>Email:</strong> <a href="mailto:lauparadiseadventure@gmail.com">lauparadiseadventure@gmail.com</a></p>
                <p><strong>Phone:</strong> <a href="tel:+255683163219">+255 683 163 219</a></p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Lau Paradise Adventures</strong></p>
            <p>Tanzania's premier tour operator</p>
            <p style="margin-top: 15px;">
                <a href="mailto:lauparadiseadventure@gmail.com" style="color: #1a4d3e;">lauparadiseadventure@gmail.com</a> | 
                <a href="tel:+255683163219" style="color: #1a4d3e;">+255 683 163 219</a>
            </p>
            <p style="margin-top: 10px; font-size: 11px; color: #999;">
                This is an automated email. Please do not reply directly to this message.
            </p>
        </div>
    </div>
</body>
</html>







