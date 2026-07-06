<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $body['title'] ?? 'Email' }}</title>
    <style>
        body,
        table,
        td {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f5;
        }

        @media (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }

            .content-padding {
                padding: 28px 20px !important;
            }

            .header-padding {
                padding: 20px !important;
            }
        }
    </style>
</head>

<body>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
        style="background-color:#f4f4f5; padding:40px 0;">
        <tr>
            <td align="center">

                <table role="presentation" class="email-container" width="600" cellpadding="0" cellspacing="0"
                    style="max-width:600px; width:100%; background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.06); border:1px solid #e4e4e7;">

                    <!-- HEADER -->
                    <tr>
                        <td class="header-padding" style="background-color:#18181b; padding:24px 32px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td valign="middle" width="40">
                                        <img src="{{ $body['logo'] ?? '' }}" alt="Logo" width="36"
                                            height="36" style="display:block; border-radius:6px;">
                                    </td>
                                    <td valign="middle" style="padding-left:12px;">
                                        <p
                                            style="margin:0; color:#fafafa; font-size:15px; font-weight:600; letter-spacing:0.2px;">
                                            {{ $body['app_name'] ?? 'Document Monitoring Tool' }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ACCENT BAR -->
                    <tr>
                        <td style="background-color:#f97316; height:3px; line-height:3px; font-size:0;">&nbsp;</td>
                    </tr>

                    <!-- CONTENT -->
                    <tr>
                        <td class="content-padding" style="padding:40px 32px;">

                            <h1
                                style="margin:0 0 8px; color:#18181b; font-size:22px; font-weight:700; line-height:1.3;">
                                {{ $body['title'] ?? 'Hello!' }}
                            </h1>

                            @if (isset($body['Header']))
                                <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 0 20px;">
                                    <tr>
                                        <td
                                            style="background-color:#fff7ed; border:1px solid #fed7aa; border-radius:6px; padding:6px 12px;">
                                            <p
                                                style="margin:0; color:#c2410c; font-size:13px; font-weight:600; font-family:monospace, monospace;">
                                                {{ $body['Header'] }}
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            <p style="margin:0 0 28px; color:#52525b; font-size:15px; line-height:1.65;">
                                {{ $body['message'] ?? 'This is a test email.' }}
                            </p>

                            @if (isset($body['button']))
                                <table role="presentation" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="border-radius:8px; background-color:#f97316;">
                                            <a href="{{ $body['button']['url'] }}"
                                                style="display:inline-block; padding:13px 30px; color:#ffffff; text-decoration:none; font-size:14px; font-weight:600; letter-spacing:0.2px;">
                                                {{ $body['button']['text'] }}
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td
                            style="background-color:#fafafa; border-top:1px solid #e4e4e7; padding:20px 32px; text-align:center;">
                            <p style="margin:0; color:#a1a1aa; font-size:12px; line-height:1.6;">
                                {{ $body['footer'] ?? 'Please do not reply to this email. Thank you.' }}
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
