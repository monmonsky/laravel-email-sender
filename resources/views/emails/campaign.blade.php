<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        /* Reset styles */
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f5;
        }

        table {
            border-collapse: collapse;
        }

        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        /* Header */
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            text-align: center;
        }

        .email-logo {
            margin-bottom: 10px;
        }

        .email-logo svg {
            width: 60px;
            height: 60px;
            fill: #ffffff;
            display: inline-block;
        }

        .email-title {
            color: #ffffff;
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        /* Content */
        .email-content {
            padding: 40px 30px;
            background-color: #ffffff;
        }

        .email-content h1,
        .email-content h2,
        .email-content h3 {
            color: #1f2937;
            margin-top: 0;
        }

        .email-content p {
            margin: 16px 0;
            color: #4b5563;
        }

        .email-content a {
            color: #667eea;
            text-decoration: none;
        }

        .email-content a:hover {
            text-decoration: underline;
        }

        /* Button */
        .email-button {
            display: inline-block;
            padding: 14px 28px;
            margin: 20px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
        }

        .email-button:hover {
            opacity: 0.9;
            text-decoration: none !important;
        }

        /* Footer */
        .email-footer {
            background-color: #f9fafb;
            padding: 30px 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .email-footer p {
            margin: 8px 0;
            font-size: 14px;
            color: #6b7280;
        }

        .email-footer a {
            color: #667eea;
            text-decoration: none;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-content {
                padding: 30px 20px;
            }

            .email-header {
                padding: 30px 20px;
            }

            .email-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f4f5; padding: 20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-container">
                    <!-- Header -->
                    <tr>
                        <td class="email-header">
                            <div class="email-logo">
                                <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                            </div>
                            <h1 class="email-title">Email Campaign</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td class="email-content">
                            {!! $content !!}
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="email-footer">
                            <p>You received this email because you're subscribed to our mailing list.</p>
                            <p>
                                <a href="{{ $unsubscribeUrl ?? '#' }}">Unsubscribe</a> |
                                <a href="{{ $preferencesUrl ?? '#' }}">Update Preferences</a>
                            </p>
                            <p style="margin-top: 20px; font-size: 12px; color: #9ca3af;">
                                &copy; {{ date('Y') }} {{ $senderName ?? 'Email Campaign Manager' }}. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
