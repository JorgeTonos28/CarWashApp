<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ticket #{{ $ticket->id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @media print {
            @page { size: 80mm auto; margin: 0mm; }
            body { margin: 0mm; }
        }

        :root { --ink: #000; }

        body {
            font: 12px/1.3 "Inter", sans-serif;
            background: #fff;
            color: var(--ink);
            width: 80mm;
            margin: 0 auto;
        }

        .ticket { padding: 10px 5px; }
        .center { text-align: center; }
        .right { text-align: right; }
        .muted { opacity: 0.8; font-size: 11px; }
        .bold { font-weight: bold; }

        .rule {
            height: 1px;
            margin: 8px 0;
            border-bottom: 1px dashed var(--ink);
        }

        .title { font-weight: 800; font-size: 16px; letter-spacing: 1px; text-transform: uppercase; }

        .tbl { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .tbl thead th { text-align: left; font-size: 11px; border-bottom: 1px solid #000; padding-bottom: 4px; }
        .tbl td { padding: 4px 0; vertical-align: top; }
        .col-qty { width: 15%; text-align: center; }
        .col-desc { width: 60%; }
        .col-amt { width: 25%; text-align: right; }

        .totals { width: 100%; margin-top: 10px; font-size: 13px; }
        .totals td { padding: 2px 0; }
        .amount { font-weight: 900; font-size: 18px; margin-top: 5px; }

        .qr-container { margin-top: 15px; display: flex; flex-direction: column; align-items: center; }
        .qr-img { width: 100px; height: 100px; object-fit: contain; }
        .qr-text { font-size: 12px; font-weight: 600; margin-top: 4px; }

        ::-webkit-scrollbar { display: none; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="center">
            @if(isset($appearance) && $appearance->logo_updated_at)
                <img src="{{ asset('images/logo.png') }}?v={{ $appearance->logo_updated_at->timestamp }}" alt="Logo" style="max-height: 60px; margin: 0 auto 6px;">
            @endif
            <div class="title">{{ $appearance->business_name ?? 'CarWash App' }}</div>
            @if(!empty($appearance?->business_address))
                <div class="muted">{{ $appearance->business_address }}</div>
            @endif
            @if(!empty($appearance?->tax_id))
                <div class="muted">RNC/ID: {{ $appearance->tax_id }}</div>
            @endif
        </div>

        <div class="rule"></div>

        <div style="display: flex; justify-content: space-between; font-size: 11px;">
            <div>
                <div>TICKET: <strong>#{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}</strong></div>
                <div>FECHA: {{ $ticket->created_at->format('d/m/Y h:i A') }}</div>
            </div>
            <div class="right">
                <div>CLIENTE: {{ \Illuminate\Support\Str::limit($ticket->customer_name ?? 'Visitante', 15) }}</div>
                <div>LAVADOR: {{ \Illuminate\Support\Str::limit($ticket->washer?->name ?? 'N/A', 15) }}</div>
            </div>
        </div>

        @if($ticket->vehicle)
            <div class="muted center" style="margin-top: 4px;">
                VEHÍCULO: {{ $ticket->vehicle->plate ?? '---' }} ({{ $ticket->vehicle->model ?? '' }})
            </div>
        @endif

        <table class="tbl">
            <thead>
                <tr>
                    <th class="col-qty">CANT</th>
                    <th class="col-desc">DESC</th>
                    <th class="col-amt">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ticket->details as $detail)
                    <tr>
                        <td class="col-qty">{{ $detail->quantity }}</td>
                        <td class="col-desc">
                            {{ $detail->description ?? $detail->service?->name ?? $detail->product?->name ?? $detail->drink?->name ?? 'Detalle' }}
                            @if($detail->service)
                                <br><span class="muted">({{ $detail->service->name }})</span>
                            @endif
                        </td>
                        <td class="col-amt">${{ number_format($detail->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="rule"></div>

        <table class="totals">
            <tr>
                <td class="right bold">SUBTOTAL:</td>
                <td class="right">${{ number_format($ticket->total_amount, 2) }}</td>
            </tr>
            @if($ticket->discount_total > 0)
                <tr>
                    <td class="right bold">DESCUENTO:</td>
                    <td class="right">-${{ number_format($ticket->discount_total, 2) }}</td>
                </tr>
            @endif
        </table>

        <div style="display:flex; justify-content:space-between; align-items:center; border-top: 1px solid #000; margin-top: 5px; padding-top: 5px;">
            <div class="amount">TOTAL</div>
            <div class="amount">${{ number_format($ticket->total_amount - ($ticket->discount_total ?? 0), 2) }}</div>
        </div>

        <div class="center" style="margin-top: 15px; font-weight: 600;">*** GRACIAS POR SU VISITA ***</div>

        @if(isset($appearance) && $appearance->qr_code_updated_at)
            <div class="qr-container">
                <img src="{{ asset('images/qr_code.png') }}?v={{ $appearance->qr_code_updated_at->timestamp }}" class="qr-img" alt="QR">
                <div class="qr-text">{{ $appearance->qr_description ?? 'Escanéame' }}</div>
            </div>
        @endif
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
