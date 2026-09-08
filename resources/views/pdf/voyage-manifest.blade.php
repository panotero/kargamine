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

        .leg-block {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .leg-title {
            background: #e8e8e8;
            padding: 8px;
            font-weight: bold;
            font-size: 13px;
            border: 1px solid #ccc;
        }

        .section {
            margin-bottom: 14px;
        }

        .section-title {
            background: #f2f2f2;
            padding: 6px 8px;
            font-weight: bold;
            font-size: 11px;
            border: 1px solid #ddd;
        }

        .cargo-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .cargo-table th,
        .cargo-table td {
            border: 1px solid #ddd;
            padding: 5px;
            font-size: 9.5px;
        }

        .cargo-table th {
            background: #f5f5f5;
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
            font-size: 9px;
            color: #777;
            margin-top: 4px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="company-name">ABC Logistics Corporation</div>
        <div class="title">Voyage Manifest - {{ $vessel->name }}</div>
        <p class="footnote">Consolidated load/unload list covering every leg of this vessel's current schedule.
            Generated {{ now()->format('F d, Y g:i A') }}.</p>
    </div>

    @forelse ($legs as $leg)
        @php
            $originLabel = $leg->originPort ? ($leg->originPort->location->name ?? '-') . ' - ' . $leg->originPort->name : '-';
            $destLabel = $leg->destinationPort ? ($leg->destinationPort->location->name ?? '-') . ' - ' . $leg->destinationPort->name : '-';
            $units = $leg->units;
        @endphp
        <div class="leg-block">
            <div class="leg-title">Leg {{ $leg->voyage_leg }} &bull; {{ $leg->voyage_mnemonic }} &bull;
                {{ $originLabel }} &rarr; {{ $destLabel }}</div>

            <div class="section">
                <div class="section-title">Load List &bull; To Be Loaded at {{ $originLabel }}
                    ({{ $units->count() }})</div>
                <table class="cargo-table">
                    <thead>
                        <tr>
                            <th width="4%">#</th>
                            <th width="10%">Booking</th>
                            <th width="16%">Client</th>
                            <th width="12%">Container No.</th>
                            <th width="18%">Container Type</th>
                            <th width="9%">Equiv. TEU</th>
                            <th width="18%">Consignee</th>
                            <th width="13%">BOL No.</th>
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
                                <td>{{ $unit->bookingLine->consignee_name ?? '-' }}</td>
                                <td>{{ $unit->booking->billOfLading->bol_number ?? 'Not yet issued' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align:center;">No containers to load on this leg.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="section">
                <div class="section-title">Unload List &bull; To Be Unloaded at {{ $destLabel }}
                    ({{ $units->count() }})</div>
                <table class="cargo-table">
                    <thead>
                        <tr>
                            <th width="4%">#</th>
                            <th width="10%">Booking</th>
                            <th width="14%">Client</th>
                            <th width="11%">Container No.</th>
                            <th width="15%">Container Type</th>
                            <th width="8%">Equiv. TEU</th>
                            <th width="10%">Status</th>
                            <th width="14%">Consignee</th>
                            <th width="10%">BOL No.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($units as $unit)
                            @php
                                $isRelay = $unit->relay_port_id && $unit->relay_port_id === $leg->destination_port_id;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $unit->booking->code ?? '-' }}</td>
                                <td>{{ $unit->booking->client->company_name ?? '-' }}</td>
                                <td>{{ $unit->containerAsset->container_no ?? 'Not yet assigned' }}</td>
                                <td>{{ $unit->bookingLine->container->name ?? '-' }} /
                                    {{ $unit->bookingLine->containerClass->class ?? '-' }} /
                                    {{ $unit->bookingLine->containerSize->size ?? '-' }}</td>
                                <td>{{ $unit->equivalent_teu !== null ? number_format($unit->equivalent_teu, 2) : '-' }}</td>
                                <td>{{ $isRelay ? 'Relay / Transfer' : 'Final Discharge' }}</td>
                                <td>{{ $unit->bookingLine->consignee_name ?? '-' }}</td>
                                <td>{{ $unit->booking->billOfLading->bol_number ?? 'Not yet issued' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align:center;">No containers to unload on this leg.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <p style="text-align:center;">This vessel has no schedule legs yet.</p>
    @endforelse

    <p class="footnote">Equivalent TEU applies to Flat Rack, Rolling Cargo, and Loose Cargo only, per the SOP -
        standard container sizes are shown under Container Type instead. "Relay / Transfer" means the container is
        being set down at that port to continue on a later leg, not for final delivery.</p>

    <div class="footer">Voyage Manifest Generated Systematically &bull; Confidential Document</div>

</body>

</html>
