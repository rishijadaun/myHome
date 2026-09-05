<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no" />
    <meta name="x-apple-disable-message-reformatting" />
    <title>StayNest Email Verification Code</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style type="text/css">
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100% !important; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b; }
        @media only screen and (max-width: 600px) {
            .container-table { width: 100% !important; padding: 12px !important; }
            .otp-code { font-size: 32px !important; letter-spacing: 6px !important; }
            .content-cell { padding: 24px 18px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc;">

    <!-- Preheader Text -->
    <div style="display: none; max-height: 0px; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
        Your StayNest verification code is {{ $otp }}. Use this 6-digit code to verify your email address.
    </div>

    <!-- Main Wrapper Table -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; padding: 30px 10px;">
        <tr>
            <td align="center">
                
                <!-- Card Container Table (Max 580px) -->
                <table class="container-table" border="0" cellpadding="0" cellspacing="0" width="580" style="width: 580px; max-width: 580px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
                    
                    <!-- Header Banner -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #0f766e 0%, #115e59 100%); background-color: #0f766e; padding: 28px 24px;">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <div style="display: inline-block; background-color: #ffffff; width: 42px; height: 42px; border-radius: 12px; line-height: 42px; text-align: center; font-size: 22px; font-weight: 900; color: #0f766e; margin-bottom: 8px;">
                                            🏠
                                        </div>
                                        <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px; line-height: 1.2;">
                                            StayNest
                                        </h1>
                                        <p style="margin: 4px 0 0 0; font-size: 12px; color: #ccfbf1; font-weight: 500; letter-spacing: 0.5px;">
                                            Verified Residential Platform
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Content Body -->
                    <tr>
                        <td class="content-cell" style="padding: 36px 36px 28px 36px;">
                            
                            <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 24px; color: #1e293b; font-weight: 600;">
                                Hello {{ $userName ?: 'there' }},
                            </p>

                            <p style="margin: 0 0 24px 0; font-size: 14px; line-height: 22px; color: #475569;">
                                We received a request to update your registered email address on your <strong>StayNest</strong> account. Please use the 6-digit verification code below to confirm this change:
                            </p>

                            <!-- OTP Box -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 24px 0;">
                                <tr>
                                    <td align="center" style="background-color: #f0fdfa; border: 2px dashed #0d9488; border-radius: 12px; padding: 20px 10px;">
                                        <p style="margin: 0 0 6px 0; font-size: 11px; font-weight: 800; color: #0f766e; text-transform: uppercase; letter-spacing: 1px;">
                                            Your Verification Code
                                        </p>
                                        <div class="otp-code" style="font-family: 'Courier New', Courier, monospace; font-size: 38px; font-weight: 800; color: #0f766e; letter-spacing: 10px; line-height: 1; padding: 6px 0;">
                                            {{ $otp }}
                                        </div>
                                        <p style="margin: 6px 0 0 0; font-size: 12px; color: #64748b;">
                                            ⏱️ Valid for <strong>10 minutes</strong> only
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Instructions & Security Notice -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border-radius: 8px; border-left: 4px solid #0d9488; padding: 14px 16px; margin-bottom: 24px;">
                                <tr>
                                    <td style="font-size: 12px; line-height: 18px; color: #475569;">
                                        <strong>🔒 Security Reminder:</strong> Never share this code with anyone. StayNest support staff will never ask for your OTP or password.
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0; font-size: 13px; line-height: 20px; color: #64748b;">
                                If you did not initiate this request, you can safely ignore this email. No changes will be made to your account.
                            </p>

                            <p style="margin: 24px 0 0 0; font-size: 13px; line-height: 20px; color: #334155; font-weight: 600;">
                                Best regards,<br />
                                <span style="color: #0f766e;">The StayNest Security Team</span>
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f1f5f9; padding: 20px 24px; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0 0 6px 0; font-size: 11px; color: #64748b; line-height: 16px;">
                                This is an automated transactional security message from <a href="{{ config('app.url') }}" style="color: #0f766e; text-decoration: none; font-weight: 600;">StayNest</a>.
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #94a3b8; line-height: 16px;">
                                &copy; {{ date('Y') }} StayNest Residential Services. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- End Card Container -->

            </td>
        </tr>
    </table>

</body>
</html>
