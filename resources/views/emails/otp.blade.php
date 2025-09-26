<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification - SuperAuth</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .otp-box {
            background: #eff6ff;
            border: 2px solid #3b82f6;
            border-radius: 12px;
            padding: 30px;
            margin: 20px 0;
            text-align: center;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #1d4ed8;
            letter-spacing: 8px;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
        }
        .details {
            background: #f9fafb;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .details h3 {
            margin-top: 0;
            color: #374151;
        }
        .details ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .details li {
            margin: 5px 0;
        }
        .action-button {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 0;
        }
        .footer {
            background: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .otp-badge {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            margin-left: 10px;
        }
        .expiry-warning {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 OTP Verification</h1>
            <p>Your one-time password for SuperAuth</p>
        </div>
        
        <div class="content">
            <div class="otp-box">
                <h2 style="margin-top: 0; color: #1d4ed8;">
                    <span style="font-size: 24px; margin-right: 10px;">🔑</span>
                    Your OTP Code
                </h2>
                <div class="otp-code">{{ $otp }}</div>
                <p style="color: #6b7280; margin: 0;">Enter this code to complete your verification</p>
            </div>

            <div class="details">
                <h3>📋 Verification Details</h3>
                <ul>
                    <li><strong>User:</strong> {{ $user->name ?? 'Unknown User' }}</li>
                    <li><strong>Email:</strong> {{ $user->email ?? 'Unknown Email' }}</li>
                    <li><strong>Generated:</strong> {{ $otpData['created_at'] ?? now()->format('Y-m-d H:i:s T') }}</li>
                    <li><strong>Expires:</strong> {{ $otpData['expires_at'] ?? now()->addMinutes(10)->format('Y-m-d H:i:s T') }}</li>
                    <li><strong>Purpose:</strong> {{ $otpData['purpose'] ?? 'Account Verification' }}</li>
                </ul>
            </div>

            <div class="details">
                <h3>🔒 Security Information</h3>
                <ul>
                    <li><strong>IP Address:</strong> {{ $otpData['ip_address'] ?? 'Unknown' }}</li>
                    <li><strong>Device:</strong> {{ $otpData['device'] ?? 'Unknown' }}</li>
                    <li><strong>Browser:</strong> {{ $otpData['browser'] ?? 'Unknown' }}</li>
                    <li><strong>Location:</strong> {{ $otpData['location'] ?? 'Unknown' }}</li>
                </ul>
            </div>

            <div class="expiry-warning">
                <h4 style="margin-top: 0; color: #92400e;">⏰ Important</h4>
                <p style="margin-bottom: 0; color: #92400e;">
                    This OTP code will expire in <strong>{{ $otpData['expires_in'] ?? '10 minutes' }}</strong>. 
                    Please use it promptly to complete your verification.
                </p>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $otpData['verification_url'] ?? '/verify-otp' }}" class="action-button">
                    Verify OTP Code
                </a>
            </div>

            <div class="details">
                <h3>🛡️ Security Tips</h3>
                <ul>
                    <li>Never share your OTP code with anyone</li>
                    <li>SuperAuth will never ask for your OTP via phone or email</li>
                    <li>If you didn't request this OTP, please secure your account immediately</li>
                    <li>Use a secure connection when entering your OTP</li>
                    <li>Delete this email after successful verification</li>
                </ul>
            </div>

            <div style="background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px; padding: 15px; margin: 20px 0;">
                <h4 style="margin-top: 0; color: #374151;">💡 Need Help?</h4>
                <p style="margin-bottom: 0; color: #6b7280;">
                    If you're having trouble with the verification process, please contact our support team or check our help documentation.
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p>This is an automated OTP verification email from SuperAuth.</p>
            <p>If you did not request this OTP, please contact our support team immediately.</p>
            <p style="margin-top: 15px; font-size: 12px; color: #9ca3af;">
                SuperAuth v1.0.0 - The Ultimate Laravel Authentication System<br>
                Powered by Secure OTP Verification
            </p>
        </div>
    </div>
</body>
</html>
