<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Critical Security Alert - SuperAuth</title>
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
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
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
        .alert-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .alert-icon {
            color: #dc2626;
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
            background: #dc2626;
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
        .security-badge {
            display: inline-block;
            background: #dc2626;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚨 Critical Security Alert</h1>
            <p>Immediate attention required</p>
        </div>
        
        <div class="content">
            <div class="alert-box">
                <h2 style="margin-top: 0; color: #dc2626;">
                    <span class="alert-icon">⚠️</span>
                    Security Threat Detected
                </h2>
                <p><strong>Alert Level:</strong> <span class="security-badge">CRITICAL</span></p>
                <p><strong>Time:</strong> {{ $alert['timestamp'] ?? now()->format('Y-m-d H:i:s T') }}</p>
                <p><strong>User:</strong> {{ $user->name ?? 'Unknown User' }} ({{ $user->email ?? 'Unknown Email' }})</p>
            </div>

            <h2>Security Incident Details</h2>
            <div class="details">
                <h3>🔍 Incident Information</h3>
                <ul>
                    <li><strong>Type:</strong> {{ $alert['type'] ?? 'Suspicious Activity' }}</li>
                    <li><strong>Severity:</strong> {{ $alert['severity'] ?? 'High' }}</li>
                    <li><strong>IP Address:</strong> {{ $alert['ip_address'] ?? 'Unknown' }}</li>
                    <li><strong>Location:</strong> {{ $alert['location'] ?? 'Unknown' }}</li>
                    <li><strong>Device:</strong> {{ $alert['device'] ?? 'Unknown' }}</li>
                    <li><strong>Browser:</strong> {{ $alert['browser'] ?? 'Unknown' }}</li>
                </ul>
            </div>

            <div class="details">
                <h3>📊 Risk Assessment</h3>
                <ul>
                    <li><strong>Risk Score:</strong> {{ $alert['risk_score'] ?? '85' }}/100</li>
                    <li><strong>Threat Level:</strong> {{ $alert['threat_level'] ?? 'High' }}</li>
                    <li><strong>Anomaly Score:</strong> {{ $alert['anomaly_score'] ?? '92' }}/100</li>
                    <li><strong>Confidence:</strong> {{ $alert['confidence'] ?? '95' }}%</li>
                </ul>
            </div>

            <div class="details">
                <h3>🔒 Recommended Actions</h3>
                <ul>
                    <li>Immediately review user account activity</li>
                    <li>Consider temporary account suspension</li>
                    <li>Force password reset for affected user</li>
                    <li>Enable additional security measures</li>
                    <li>Monitor for further suspicious activity</li>
                </ul>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $alert['admin_url'] ?? '/admin/security' }}" class="action-button">
                    View Security Dashboard
                </a>
            </div>

            <div class="details">
                <h3>🤖 AI Analysis</h3>
                <p><strong>Pattern Detection:</strong> {{ $alert['pattern'] ?? 'Unusual login pattern detected' }}</p>
                <p><strong>Behavioral Analysis:</strong> {{ $alert['behavior'] ?? 'Significant deviation from normal user behavior' }}</p>
                <p><strong>Geographic Anomaly:</strong> {{ $alert['geo_anomaly'] ?? 'Login from unusual geographic location' }}</p>
                <p><strong>Time Anomaly:</strong> {{ $alert['time_anomaly'] ?? 'Login at unusual time for this user' }}</p>
            </div>

            <div style="background: #fef3c7; border: 1px solid #f59e0b; border-radius: 6px; padding: 15px; margin: 20px 0;">
                <h4 style="margin-top: 0; color: #92400e;">⚠️ Immediate Action Required</h4>
                <p style="margin-bottom: 0; color: #92400e;">
                    This security alert requires immediate attention. Please review the incident details and take appropriate action to protect your system and user accounts.
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p>This is an automated security alert from SuperAuth AI Security System.</p>
            <p>If you did not expect this alert, please contact your system administrator immediately.</p>
            <p style="margin-top: 15px; font-size: 12px; color: #9ca3af;">
                SuperAuth v1.0.0 - The Ultimate Laravel Authentication System<br>
                Powered by AI-Powered Security Monitoring
            </p>
        </div>
    </div>
</body>
</html>
