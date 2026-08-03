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
            padding: 8px;
            font-size: 11px;
        }

        .cargo-table th {
            background: #f5f5f5;
        }

        .flags {
            font-size: 10px;
            color: #b5790e;
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
        <div class="title">Bill of Lading</div>
    </div>

    <div class="section">
        <div class="section-title">Shipment Information</div>
        <table class="info-table">
            <tr>
                <td width="25%"><strong>BOL Number</strong></td>
                <td>{{ $bol->bol_number }}</td>
            </tr>
            <tr>
                <td><strong>Booking Code</strong></td>
                <td>{{ $booking->code }}</td>
            </tr>
            <tr>
                <td><strong>Shipper / Client</strong></td>
                <td>{{ $booking->client->company_name }} ({{ $booking->client->customer_code }})</td>
            </tr>
            @php
                // Route/lane moved to per-line (each cargo line can ship to a
                // different destination) - the booking header no longer has
                // a single lane. Show the first line's route; flag it if the
// other lines don't all agree, rather than silently picking one.
                $firstLine = $booking->lines->first();
                $sameRoute = $booking->lines->every(
                    fn($l) => $l->origin_port_id === $firstLine?->origin_port_id &&
                        $l->destination_port_id === $firstLine?->destination_port_id,
                );
            @endphp
            <tr>
                <td><strong>Port of Loading</strong></td>
                <td>{{ $firstLine?->originPort?->name ?? '-' }}
                    ({{ $firstLine?->originPort?->code ?? '-' }}){{ $sameRoute ? '' : ' *' }}</td>
            </tr>
            <tr>
                <td><strong>Port of Discharge</strong></td>
                <td>{{ $firstLine?->destinationPort?->name ?? '-' }}
                    ({{ $firstLine?->destinationPort?->code ?? '-' }}){{ $sameRoute ? '' : ' *' }}</td>
            </tr>
            @unless ($sameRoute)
                <tr>
                    <td colspan="2" style="font-size:10px;color:#b5790e;">* This booking's cargo lines ship to
                        different destinations - see the Cargo &amp; Container Detail table below for each line's
                        actual route.</td>
                </tr>
            @endunless
            <tr>
                <td><strong>Booking Date</strong></td>
                <td>{{ $booking->booking_date?->format('F d, Y') ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Date Issued</strong></td>
                <td>{{ $bol->issued_at->format('F d, Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Cargo &amp; Container Detail</div>
        <table class="cargo-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="14%">Route</th>
                    <th width="16%">Container</th>
                    <th width="13%">Container No.</th>
                    <th width="10%">Seal No.</th>
                    <th width="14%">Description</th>
                    <th width="10%">Weight (kg)</th>
                    <th width="10%">Volume (m&sup3;)</th>
                    <th width="8%">Flags</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($booking->lines as $line)
                    @forelse ($line->containerUnits as $unit)
                        <tr>
                            <td>{{ $unit->unit_index }}</td>
                            <td>{{ $line->originPort?->code ?? '-' }} &rarr;
                                {{ $line->destinationPort?->code ?? '-' }}</td>
                            <td>{{ $line->container->name ?? '-' }} / {{ $line->containerClass->class ?? '-' }} /
                                {{ $line->containerSize->size ?? '-' }}</td>
                            <td>{{ $unit->containerAsset->container_no ?? 'Not yet assigned' }}</td>
                            <td>{{ $unit->seal_no ?? 'Pending seal' }}</td>
                            <td>{{ $line->description ?? '-' }}</td>
                            <td>{{ $line->weight_kg !== null ? number_format($line->weight_kg, 2) : '-' }}</td>
                            <td>{{ $line->volume_cbm !== null ? number_format($line->volume_cbm, 2) : '-' }}</td>
                            <td class="flags">
                                @if ($line->is_hazardous)
                                    HAZMAT
                                @endif
                                @if ($line->is_hazardous && $line->is_fragile)
                                    /
                                @endif
                                @if ($line->is_fragile)
                                    FRAGILE
                                @endif
                            </td>
                        </tr>
                    @empty
                    @endforelse
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="signature-section">
        <table class="signature-row">
            <tr>
                <td>
                    <div class="signature-box">Authorized Representative<br>(ABC Logistics Corporation)</div>
                </td>
                <td>
                    <div class="signature-box">Received By<br>({{ $booking->client->company_name }})</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">Bill of Lading Generated Systematically • Confidential Document</div>

</body>

</html>
