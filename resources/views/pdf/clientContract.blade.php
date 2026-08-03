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

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
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
            display: inline-block;
        }

        .signature-row {
            width: 100%;
        }

        .signature-row td {
            text-align: center;
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
        <div class="title">Signed Client Contract</div>
    </div>

    <div class="section">
        <div class="section-title">Contract Information</div>
        <table class="info-table">
            <tr>
                <td width="25%"><strong>Contract Code</strong></td>
                <td>{{ $contract->code }}</td>
            </tr>
            <tr>
                <td><strong>Client / Company</strong></td>
                <td>{{ $contract->client->company_name }}</td>
            </tr>
            <tr>
                <td><strong>Customer Code</strong></td>
                <td>{{ $contract->client->customer_code }}</td>
            </tr>
            <tr>
                <td><strong>Registered Address</strong></td>
                <td>{{ $contract->client->formattedPrimaryAddress() }}</td>
            </tr>
            <tr>
                <td><strong>Source Proposal</strong></td>
                <td>{{ $contract->proposal->code ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Signed Date</strong></td>
                <td>{{ $contract->signed_date ? $contract->signed_date->format('F d, Y') : '-' }}</td>
            </tr>
            <tr>
                <td><strong>Valid From</strong></td>
                <td>{{ $contract->valid_from->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td><strong>Valid To</strong></td>
                <td>{{ $contract->valid_to->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td><strong>Prepared By</strong></td>
                <td>{{ $contract->creator?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Date Generated</strong></td>
                <td>{{ now()->format('F d, Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Contracted Rates</div>
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
                @foreach ($contract->rates as $index => $rate)
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
            This contract is entered into by ABC Logistics Corporation and the client named above,
            binding the rates listed herein for the validity period stated. Rates quoted are final
            and shall not be subject to further discount adjustments unless a new contract supersedes
            this one.
        </p>
        <p>
            Fuel surcharges, toll fees, port charges, and other government-imposed fees not explicitly
            included in this contract shall be billed separately when applicable. Delivery schedules
            remain subject to vessel availability, weather conditions, port congestion, and other
            circumstances beyond the control of the carrier.
        </p>
        <p>
            This contract remains in effect from the Valid From date through the Valid To date stated
            above, unless terminated earlier by either party in writing.
        </p>
    </div>

    <div class="signature-section">
        <table class="signature-row">
            <tr>
                <td>
                    <div class="signature-box">Authorized Representative<br>(ABC Logistics Corporation)</div>
                </td>
                <td>
                    <div class="signature-box">Authorized Signatory<br>({{ $contract->client->company_name }})</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">Contract Generated Systematically • Confidential Document</div>

</body>

</html>
