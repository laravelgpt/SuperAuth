<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Notification - SuperAuth</title>
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
        .login-box {
            background: #ecfdf5;
            border: 1px solid #10b981;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .login-icon {
            color: #10b981;
            font-size: 24px;
            margin-right: 10px;
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
            background: #10b981;
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
        .success-badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            margin-left: 10px;
        }
        .security-info {
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
            <h1>🔐 Login Notification</h1>
            <p>New login detected on your account</p>
        </div>
        
        <div class="content">
            <div class="login-box">
                <h2 style="margin-top: 0; color: #10b981;">
                    <span class="login-icon">✅</span>
                    Successful Login
                </h2>
                <p><strong>Status:</strong> <span class="success-badge">SUCCESSFUL</span></p>
                <p><strong>Time:</strong> {{ $login['timestamp'] ?? now()->format('Y-m-d H:i:s T') }}</p>
                <p><strong>User:</strong> {{ $user->name ?? 'Unknown User' }} ({{ $user->email ?? 'Unknown Email' }})</p>
            </div>

            <h2>Login Details</h2>
            <div class="details">
                <h3>🔍 Login Information</h3>
                <ul>
                    <li><strong>IP Address:</strong> {{ $login['ip_address'] ?? 'Unknown' }}</li>
                    <li><strong>Location:</strong> {{ $login['location'] ?? 'Unknown' }}</li>
                    <li><strong>Device:</strong> {{ $login['device'] ?? 'Unknown' }}</li>
                    <li><strong>Browser:</strong> {{ $login['browser'] ?? 'Unknown' }}</li>
                    <li><strong>Operating System:</strong> {{ $login['os'] ?? 'Unknown' }}</li>
                    <li><strong>User Agent:</strong> {{ $login['user_agent'] ?? 'Unknown' }}</li>
                </ul>
            </div>

            <div class="details">
                <h3>📊 Security Analysis</h3>
                <ul>
                    <li><strong>Risk Score:</strong> {{ $login['risk_score'] ?? '25' }}/100</li>
                    <li><strong>Threat Level:</strong> {{ $login['threat_level'] ?? 'Low' }}</li>
                    <li><strong>Anomaly Score:</strong> {{ $login['anomaly_score'] ?? '15' }}/100</li>
                    <li><strong>Confidence:</strong> {{ $login['confidence'] ?? '95' }}%</li>
                </ul>
            </div>

            <div class="details">
                <h3>🤖 AI Analysis</h3>
                <p><strong>Pattern Detection:</strong> {{ $login['pattern'] ?? 'Normal login pattern detected' }}</p>
                <p><strong>Behavioral Analysis:</strong> {{ $login['behavior'] ?? 'Consistent with user behavior patterns' }}</p>
                <p><strong>Geographic Analysis:</strong> {{ $login['geo_analysis'] ?? 'Login from familiar location' }}</p>
                <p><strong>Time Analysis:</strong> {{ $login['time_analysis'] ?? 'Login at expected time for this user' }}</p>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $login['dashboard_url'] ?? '/dashboard' }}" class="action-button">
                    View Dashboard
                </a>
            </div>

            <div class="security-info">
                <h4 style="margin-top: 0; color: #92400e;">🛡️ Security Notice</h4>
                <p style="margin-bottom: 0; color: #92400e;">
                    This login appears to be legitimate based on our AI analysis. If you recognize this activity, no action is required. 
                    If you don't recognize this login, please secure your account immediately.
                </p>
            </div>

            <div class="details">
                <h3>🔒 Security Recommendations</h3>
                <ul>
                    <li>Regularly review your login history</li>
                    <li>Use strong, unique passwords</li>
                    <li>Enable two-factor authentication</li>
                    <li>Keep your devices and browsers updated</li>
                    <li>Log out from shared or public devices</li>
                </ul>
            </div>

            <div style="background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px; padding: 15px; margin: 20px 0;">
                <h4 style="margin-top: 0; color: #374151;">💡 Need Help?</h4>
                <p style="margin-bottom: 0; color: #6b7280;">
                    If you have any concerns about this login or need assistance with your account security, 
                    please contact our support team.
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p>This is an automated login notification from SuperAuth AI Security System.</p>
            <p>If you did not expect this login, please contact our support team immediately.</p>
            <p style="margin-top: 15px; font-size: 12px; color: #9ca3af;">
                SuperAuth v1.0.0 - The Ultimate Laravel Authentication System<br>
                Powered by AI-Powered Security Monitoring
            </p>
        </div>
    </div>
</body>
</html>
