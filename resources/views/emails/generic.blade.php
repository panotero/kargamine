<!DOCTYPE html>
<html>

<head>
    <link rel="icon" type="image/x-icon" href="">
    <meta charset="utf-8">
    <title>Email</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #eef1f5;
            padding: 40px 0;
            color: #333;
            margin: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .header {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.6);
            padding: 20px 30px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .header img {
            height: 36px;
            width: auto;
        }

        .content {
            padding: 40px 30px;
        }

        h1 {
            color: #111827;
            font-size: 24px;
            margin-bottom: 15px;
        }

        p {
            color: #374151;
            line-height: 1.6;
            font-size: 15px;
            margin-bottom: 25px;
        }

        .btn {
            display: inline-block;
            background-color: #2563eb;
            color: #fff !important;
            padding: 12px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            letter-spacing: 0.3px;
            transition: background 0.2s ease;
        }

        .btn:hover {
            background-color: #1e40af;
        }

        .footer {
            text-align: center;
            padding: 20px 30px;
            font-size: 13px;
            color: #6b7280;
            background: rgba(255, 255, 255, 0.6);
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 600px) {
            .email-container {
                border-radius: 0;
                box-shadow: none;
            }

            .content {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <img src="" alt="TESDA Logo" style="height:40px; width:auto; display:block;">
            <h1> Document Monitoring Tool </h1>
        </div>

        <div class="content">

            <h1>{{ $body['title'] ?? 'Hello!' }}</h1>

            <h1>{{ $body['docControlNumber'] ?? '' }}</h1>
            <p>{{ $body['message'] ?? 'This is a test email.' }}</p>

            <br />
            <br />


            @if (isset($body['button']))
                <a href="{{ $body['button']['url'] }}" class="btn">
                    {{ $body['button']['text'] }}
                </a>
            @endif
        </div>

        <div class="footer">
            <p>Please do not reply to this email. Thank you.</p>
        </div>
    </div>
</body>

</html>
