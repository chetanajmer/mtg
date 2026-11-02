<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset your password - More Than Giftz</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: #f0f1f3; padding: 30px; border-radius: 10px;">
            <h1 style="color: #15171a; margin-top: 0;">Reset your password</h1>

            <p>We received a request to reset the password for your More Than Giftz corporate portal account.</p>

            <p>
                <a href="{{ $resetUrl }}" style="background: #15171a; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">Choose a new password</a>
            </p>

            <p style="font-size: 14px; color: #666;">This link expires in {{ $minutes }} minutes. If you didn't ask to reset your password, you can ignore this email; your password has not been changed.</p>
        </div>
    </div>
</body>
</html>
