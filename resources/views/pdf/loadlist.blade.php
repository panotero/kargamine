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

        .cargo-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .cargo-table th,
        .cargo-table td {
            border: 1px solid #ddd;
            padding: 7px;
            font-size: 10.5px;
        }

        .cargo-table th {
            background: #f5f5f5;
        }

        .summary-table {
            width: 40%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .summary-table td {
            padding: 6px;
            border: 1px solid #ddd;
        }

        .footer {
            position: fixed;
            bottom: -10mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
        }

        .footnote {
            font-size: 10px;
            color: #777;
            margin-top: 6px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="company-name">ABC Logistics Corporation</div>
        <div class="title">Vessel Loadlist</div>
    </div>

    <div class="section">
        <div class="section-title">Voyage Information</div>
        <table class="info-table">
            <tr>
                <td width="25%"><strong>Voyage Mnemonic</strong></td>
                <td>{{ $voyage->voyage_mnemonic }}</td>
            </tr>
            <tr>
                <td><strong>Vessel</strong></td>
                <td>{{ $voyage->vessel_name }} - Leg {{ $voyage->voyage_leg }}</td>
            </tr>
            <tr>
                <td><strong>Port of Origin</strong></td>
                <td>{{ $voyage->originPort->name ?? '-' }} ({{ $voyage->originPort->code ?? '-' }})</td>
            </tr>
            <tr>
                <td><strong>Port of Destination</strong></td>
                <td>{{ $voyage->destinationPort->name ?? '-' }} ({{ $voyage->destinationPort->code ?? '-' }})</td>
            </tr>
            <tr>
                <td><strong>Estimated Departure</strong></td>
                <td>{{ $voyage->estimated_departure_at?->format('F d, Y') ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Estimated Arrival</strong></td>
                <td>{{ $voyage->estimated_arrival_at?->format('F d, Y') ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Containers Assigned to This Voyage</div>
        <table class="cargo-table">
            <thead>
                <tr>
                    <th width="4%">#</th>
                    <th width="10%">Booking</th>
                    <th width="14%">Client</th>
                    <th width="11%">Container No.</th>
                    <th width="14%">Container Type</th>
                    <th width="8%">Equiv. TEU</th>
                    <th width="9%">Relay Port</th>
                    <th width="14%">Consignee</th>
                    <th width="10%">BOL No.</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($units as $unit)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $unit->booking->code ?? '-' }}</td>
                        <td>{{ $unit->booking->client->company_name ?? '-' }}</td>
                        <td>{{ $unit->containerAsset->container_no ?? 'Not yet assigned' }}</td>
                        <td>{{ $unit->bookingLine->container->name ?? '-' }} /
                            {{ $unit->bookingLine->containerClass->class ?? '-' }} /
                            {{ $unit->bookingLine->containerSize->size ?? '-' }}</td>
                        <td>{{ $unit->equivalent_teu !== null ? number_format($unit->equivalent_teu, 2) : '-' }}</td>
                        <td>{{ $unit->relayPort->code ?? '-' }}</td>
                        <td>{{ $unit->bookingLine->consignee_name ?? '-' }}</td>
                        <td>{{ $unit->booking->billOfLading->bol_number ?? 'Not yet issued' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center;">No containers assigned to this voyage yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <p class="footnote">Equivalent TEU applies to Flat Rack, Rolling Cargo, and Loose Cargo only, per the SOP -
            standard container sizes are shown under Container Type instead.</p>
    </div>

    <div class="section">
        <div class="section-title">Summary</div>
        <table class="summary-table">
            <tr>
                <td><strong>Total Containers</strong></td>
                <td>{{ $units->count() }}</td>
            </tr>
            <tr>
                <td><strong>Special Cargo Equivalent TEU Subtotal</strong></td>
                <td>{{ number_format($units->sum('equivalent_teu'), 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">Vessel Loadlist Generated Systematically &bull; Confidential Document</div>

</body>

</html>
