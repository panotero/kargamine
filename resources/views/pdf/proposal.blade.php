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

        .page-break {
            page-break-after: always;
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
        <div class="title">Transportation Rate Proposal</div>
    </div>

    <div class="section">
        <div class="section-title">Proposal Information</div>



        <table class="info-table">
            <tr>
                <td width="25%"><strong>Proposal Code</strong></td>
                <td>{{ $proposal->code }}</td>
            </tr>

            <tr>
                <td><strong>Contact Person</strong></td>
                <td>{{ $proposal->lead->contact_name }}</td>
            </tr>

            <tr>
                <td><strong>Email Address</strong></td>
                <td>{{ $proposal->lead->email }}</td>
            </tr>

            <tr>
                <td><strong>Mobile Number</strong></td>
                <td>{{ $proposal->lead->mobile }}</td>
            </tr>
            <tr>
                <td><strong>Company</strong></td>
                <td>{{ $proposal->lead->company->company_name }}</td>
            </tr>
            <tr>
                <td><strong>Company Address</strong></td>
                <td>{{ $proposal->lead->company->company_address }}</td>
            </tr>
            <tr>
                <td><strong>Authorized Signatory</strong></td>
                <td>{{ $proposal->lead->company->authorized_signatory_name }}</td>
            </tr>
            <tr>
                <td><strong>Signatory Position</strong></td>
                <td>{{ $proposal->lead->company->authorized_signatory_position }}</td>
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
                    <th width="18%">Van Class</th>
                    <th width="18%">Van Type</th>
                    <th width="12%">Van Size</th>
                    <th width="8%">Qty</th>
                    <th width="10%">Origin Service</th>
                    <th width="10%">Destination Service</th>
                    <th width="12%">Rate</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($proposal->rates as $index => $rate)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>
                            {{ $rate->routeFrom->route }}
                        </td>

                        <td>
                            {{ $rate->routeTo->route }}
                        </td>

                        <td>
                            {{ $rate->vanClass->class }}
                        </td>
                        <td>
                            {{ $rate->vanType->type }}
                        </td>

                        <td>
                            {{ $rate->vanSize->size }}
                        </td>

                        <td>
                            {{ number_format($rate->min_van_qty) }}
                        </td>

                        <td>
                            {{ $rate->serviceOrigin->mode }}
                        </td>

                        <td>
                            {{ $rate->serviceDestination->mode }}
                        </td>

                        <td>
                            ₱{{ number_format($rate->proposed_rate, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>
            This proposal contains the transportation rates offered by
            ABC Logistics Corporation. Rates are subject to route
            confirmation, service requirements, fuel adjustments,
            toll fees, and applicable government charges.
        </p>

        <p>
            All quoted rates are valid for thirty (30) days from the
            date of issuance unless otherwise agreed in writing.
        </p>

        <p>
            Service schedules remain subject to vehicle availability,
            weather conditions, traffic situations, and force majeure events.
        </p>
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
        <div class="signature-box">
            Authorized Representative
        </div>
    </div>

    <div class="page-break"></div>

    <div class="header">
        <div class="company-name">Additional Notes</div>
    </div>

    <p>
        This page demonstrates that the proposal can span multiple pages.
        Any overflow from the rates table will automatically continue onto
        succeeding pages when rendered through DomPDF.
    </p>

    <p>
        Additional route descriptions, service inclusions, client-specific
        conditions, and annexes may be added here.
    </p>

    <div class="footer">
        Proposal Generated Systematically • Confidential Document
    </div>

</body>

</html>
