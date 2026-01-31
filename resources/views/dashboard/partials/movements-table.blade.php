<div class="card overflow-hidden max-h-96 overflow-y-auto">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Concepto</th>
                <th>Fecha</th>
                <th class="text-right">Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movements as $m)
                <tr class="border-t">
                    <td class="font-semibold text-slate-900">{{ $m['description'] }}</td>
                    <td>{{ $m['date'] }}</td>
                    <td class="text-right">RD$ {{ number_format($m['amount'],2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
