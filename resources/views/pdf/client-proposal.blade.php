<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 20mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .company-name {
            font-size: 22px;
            font-weight: bold;
        }

        .title {
            font-size: 18px;
            margin-top: 5px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            background: #f2f2f2;
            padding: 8px;
            font-weight: bold;
            border: 1px solid #ddd;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 6px;
            border: 1px solid #ddd;
        }

        .rates-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .rates-table th,
        .rates-table td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 11px;
        }

        .rates-table th {
            background: #f5f5f5;
        }

        .terms {
            margin-top: 30px;
            text-align: justify;
        }

        .signature-section {
            margin-top: 60px;
        }

        .signature-box {
            width: 250px;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 50px;
        }

        .footer {
            position: fixed;
            bottom: -10mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="company-name">ABC Logistics Corporation</div>
        <div class="title">Client Rate Proposal</div>
    </div>

    <div class="section">
        <div class="section-title">Proposal Information</div>
        <table class="info-table">
            <tr>
                <td width="25%"><strong>Proposal Code</strong></td>
                <td>{{ $proposal->code }}</td>
            </tr>
            <tr>
                <td><strong>Client / Company</strong></td>
                <td>{{ $proposal->client->company_name }}</td>
            </tr>
            <tr>
                <td><strong>Customer Code</strong></td>
                <td>{{ $proposal->client->customer_code }}</td>
            </tr>
            <tr>
                <td><strong>Registered Address</strong></td>
                <td>{{ $proposal->client->registered_address }}</td>
            </tr>
            <tr>
                <td><strong>Contact Number</strong></td>
                <td>{{ $proposal->client->contact_number_1 }}</td>
            </tr>
            <tr>
                <td><strong>Prepared By</strong></td>
                <td>{{ $proposal->creator?->name }}</td>
            </tr>
            <tr>
                <td><strong>Date Generated</strong></td>
                <td>{{ now()->format('F d, Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Proposed Rates</div>
        <table class="rates-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="15%">Origin</th>
                    <th width="15%">Destination</th>
                    <th width="20%">Container</th>
                    <th width="12%">Base Rate</th>
                    <th width="16%">Discount</th>
                    <th width="17%">Final Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($proposal->rates as $index => $rate)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $rate->originPort->code ?? '-' }}</td>
                        <td>{{ $rate->destinationPort->code ?? '-' }}</td>
                        <td>{{ $rate->container->name ?? '-' }} / {{ $rate->containerClass->class ?? '-' }} /
                            {{ $rate->containerSize->size ?? '-' }}</td>
                        <td>₱{{ number_format($rate->base_rate, 2) }}</td>
                        <td>
                            @if ($rate->discount_type === 'percentage')
                                {{ number_format($rate->discount_value, 2) }}%
                            @elseif ($rate->discount_type === 'fixed')
                                ₱{{ number_format($rate->discount_value, 2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>₱{{ number_format($rate->final_rate, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="terms">
        <div class="section-title">Terms and Conditions</div>
        <p>
            Rates quoted herein are valid for thirty (30) calendar days from
            the date of issuance. Fuel surcharges, toll fees, port charges,
            and other government-imposed fees not explicitly included in the
            proposal shall be billed separately when applicable.
        </p>
        <p>
            Delivery schedules are subject to vessel availability, weather
            conditions, port congestion, and other circumstances beyond the
            control of the carrier.
        </p>
    </div>

    <div class="signature-section">
        <div class="signature-box">Authorized Representative</div>
    </div>

    <div class="footer">Proposal Generated Systematically • Confidential Document</div>

</body>

</html>
