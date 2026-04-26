<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation - VirtuGym</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { width: 80px; border-radius: 50%; }
        .title { color: #8b5cf6; }
        .details { background: #f3f4f6; padding: 15px; border-radius: 10px; margin: 20px 0; }
        .tip { background: #fef3c7; padding: 15px; border-radius: 10px; margin: 20px 0; color: #92400e; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="VirtuGym" class="logo">
            <h1 class="title">VirtuGym</h1>
        </div>
        
        <h2>Booking Confirmed! 🎉</h2>
        
        <p>Dear {{ $trainee->name }},</p>
        
        <p>Your booking with <strong>{{ $trainer->name }}</strong> has been confirmed.</p>
        
        <div class="details">
            <p><strong>📅 Date:</strong> {{ \Carbon\Carbon::parse($booking->session_date)->format('F d, Y') }}</p>
            <p><strong>⏰ Time:</strong> {{ \Carbon\Carbon::parse($booking->session_date)->format('h:i A') }}</p>
            <p><strong>⏱️ Duration:</strong> {{ $booking->duration_minutes }} minutes</p>
            <p><strong>💰 Amount Paid:</strong> ₹{{ number_format($booking->amount) }}</p>
            @if($booking->special_requests)
                <p><strong>📝 Special Requests:</strong> {{ $booking->special_requests }}</p>
            @endif
        </div>
        
        <div class="tip">
            💡 <strong>Pro Tip:</strong> Be ready 5 minutes before your session. Test your camera and microphone if it's an online session.
        </div>
        
        <p>Need to reschedule? Contact your trainer directly.</p>
        
        <div class="footer">
            <p>© 2024 VirtuGym. All rights reserved.<br>Your virtual personal trainer</p>
        </div>
    </div>
</body>
</html>