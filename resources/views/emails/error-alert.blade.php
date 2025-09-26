<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Error Alert - SuperAuth</title>
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
        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .error-icon {
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
        .error-badge {
            display: inline-block;
            background: #dc2626;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            margin-left: 10px;
        }
        .code-block {
            background: #1f2937;
            color: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            overflow-x: auto;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚨 System Error Alert</h1>
            <p>Critical error detected in SuperAuth system</p>
        </div>
        
        <div class="content">
            <div class="error-box">
                <h2 style="margin-top: 0; color: #dc2626;">
                    <span class="error-icon">⚠️</span>
                    Error Details
                </h2>
                <p><strong>Error Level:</strong> <span class="error-badge">{{ $error['level'] ?? 'CRITICAL' }}</span></p>
                <p><strong>Time:</strong> {{ $error['timestamp'] ?? now()->format('Y-m-d H:i:s T') }}</p>
                <p><strong>Environment:</strong> {{ $error['environment'] ?? 'Production' }}</p>
                <p><strong>User:</strong> {{ $error['user'] ?? 'System' }}</p>
            </div>

            <h2>Error Information</h2>
            <div class="details">
                <h3>🔍 Error Details</h3>
                <ul>
                    <li><strong>Type:</strong> {{ $error['type'] ?? 'System Error' }}</li>
                    <li><strong>Message:</strong> {{ $error['message'] ?? 'An error occurred' }}</li>
                    <li><strong>File:</strong> {{ $error['file'] ?? 'Unknown' }}</li>
                    <li><strong>Line:</strong> {{ $error['line'] ?? 'Unknown' }}</li>
                    <li><strong>URL:</strong> {{ $error['url'] ?? 'Unknown' }}</li>
                    <li><strong>IP Address:</strong> {{ $error['ip'] ?? 'Unknown' }}</li>
                    <li><strong>User Agent:</strong> {{ $error['user_agent'] ?? 'Unknown' }}</li>
                </ul>
            </div>

            <div class="details">
                <h3>📊 System Information</h3>
                <ul>
                    <li><strong>Server:</strong> {{ $error['server'] ?? 'Unknown' }}</li>
                    <li><strong>PHP Version:</strong> {{ $error['php_version'] ?? 'Unknown' }}</li>
                    <li><strong>Laravel Version:</strong> {{ $error['laravel_version'] ?? 'Unknown' }}</li>
                    <li><strong>Memory Usage:</strong> {{ $error['memory_usage'] ?? 'Unknown' }}</li>
                    <li><strong>Execution Time:</strong> {{ $error['execution_time'] ?? 'Unknown' }}</li>
                </ul>
            </div>

            @if(isset($error['stack_trace']) && $error['stack_trace'])
                <div class="details">
                    <h3>🔍 Stack Trace</h3>
                    <div class="code-block">
                        {{ $error['stack_trace'] }}
                    </div>
                </div>
            @endif

            <div class="details">
                <h3>🔧 Recommended Actions</h3>
                <ul>
                    <li>Review the error details and stack trace</li>
                    <li>Check system logs for related errors</li>
                    <li>Verify system configuration and dependencies</li>
                    <li>Consider implementing additional error handling</li>
                    <li>Monitor system performance and resource usage</li>
                </ul>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $error['admin_url'] ?? '/admin/errors' }}" class="action-button">
                    View Error Dashboard
                </a>
            </div>

            <div style="background: #fef3c7; border: 1px solid #f59e0b; border-radius: 6px; padding: 15px; margin: 20px 0;">
                <h4 style="margin-top: 0; color: #92400e;">⚠️ Immediate Action Required</h4>
                <p style="margin-bottom: 0; color: #92400e;">
                    This error requires immediate attention. Please review the error details and take appropriate action to resolve the issue.
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p>This is an automated error alert from SuperAuth Error Monitoring System.</p>
            <p>If you did not expect this alert, please contact your system administrator immediately.</p>
            <p style="margin-top: 15px; font-size: 12px; color: #9ca3af;">
                SuperAuth v1.0.0 - The Ultimate Laravel Authentication System<br>
                Powered by Advanced Error Monitoring & Recovery
            </p>
        </div>
    </div>
</body>
</html>
