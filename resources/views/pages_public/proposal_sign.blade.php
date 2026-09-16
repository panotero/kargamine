<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Proposal {{ $proposal->code }} — Kargamine</title>
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
            color: #18181b;
        }

        .wrap {
            max-width: 560px;
            margin: 48px auto;
            padding: 0 16px;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e4e4e7;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        }

        .card-header {
            background-color: #18181b;
            padding: 20px 28px;
            color: #fafafa;
        }

        .card-header p {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
        }

        .accent-bar {
            background-color: #f97316;
            height: 3px;
        }

        .card-body {
            padding: 32px 28px;
        }

        h1 {
            font-size: 20px;
            margin: 0 0 6px;
        }

        .muted {
            color: #71717a;
            font-size: 13px;
            margin: 0 0 24px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .btn-secondary {
            background: #f4f4f5;
            color: #18181b;
            border: 1px solid #d4d4d8;
        }

        .btn-primary {
            background: #f97316;
            color: #ffffff;
        }

        .section {
            border-top: 1px solid #e4e4e7;
            padding-top: 24px;
            margin-top: 24px;
        }

        .section p.label {
            font-size: 13px;
            font-weight: 600;
            color: #3f3f46;
            margin: 0 0 8px;
        }

        .steps {
            font-size: 13px;
            color: #52525b;
            line-height: 1.6;
            margin: 0 0 16px;
            padding-left: 18px;
        }

        input[type="file"] {
            display: block;
            width: 100%;
            font-size: 13px;
            padding: 10px;
            border: 1px dashed #d4d4d8;
            border-radius: 8px;
            margin-bottom: 12px;
            box-sizing: border-box;
        }

        .alert {
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
        }

        .footer {
            text-align: center;
            color: #a1a1aa;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="card">
            <div class="card-header">
                <p>Kargamine</p>
            </div>
            <div class="accent-bar"></div>
            <div class="card-body">
                <h1>Proposal {{ $proposal->code }}</h1>
                <p class="muted">
                    {{ $proposal->client->company_name ?? $proposal->lead->company->company_name ?? '' }}
                </p>

                @if ($error)
                    <div class="alert alert-error">{{ $error }}</div>
                @endif

                @if ($uploaded)
                    <div class="alert alert-success">
                        Thank you — your signed proposal was received. Our team will follow up shortly.
                    </div>
                @endif

                <a href="{{ $downloadUrl }}" class="btn btn-secondary">Download Approved Proposal</a>

                @unless ($uploaded)
                    <div class="section">
                        <p class="label">Sign &amp; Upload</p>
                        <ol class="steps">
                            <li>Download the approved proposal above.</li>
                            <li>Sign it — digitally, or print, sign by hand, and scan/photograph it.</li>
                            <li>Upload the signed copy below (PDF, JPG or PNG, up to 10MB).</li>
                        </ol>

                        <form action="{{ $uploadUrl }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="signed_document" accept=".pdf,.jpg,.jpeg,.png" required>
                            <button type="submit" class="btn btn-primary">Upload Signed Proposal</button>
                        </form>
                    </div>
                @endunless
            </div>
        </div>
        <p class="footer">This link is unique to you and expires after 30 days. Please do not share it.</p>
    </div>
</body>

</html>
