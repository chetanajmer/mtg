<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - More Than Giftz</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: #f0f1f3; padding: 30px; border-radius: 10px;">
            <h1 style="color: #15171a; margin-top: 0;">Password Reset Request</h1>

            <p>Hello,</p>

            <p>We received a request to reset your password for your More Than Giftz corporate portal account.</p>

            <p>Your new password is:</p>

            <div style="background: #15171a; color: #fff; padding: 15px; border-radius: 5px; text-align: center; font-size: 18px; font-weight: bold; margin: 20px 0;">
                {{ $password }}
            </div>

            <p>Please use this password to login to your account:</p>

            <p>
                <a href="{{ url('/corporate/login') }}" style="background: #15171a; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">Login to Corporate Portal</a>
            </p>

            <p style="font-size: 14px; color: #666;">For security reasons, we recommend changing your password after logging in.</p>

            <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

            <p style="font-size: 12px; color: #999;">If you did not request this password reset, please ignore this email or contact our support team.</p>

            <p style="font-size: 12px; color: #999;">&copy; {{ date('Y') }} More Than Giftz. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
