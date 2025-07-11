<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laba</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: left; }
        h2 { margin-bottom: 0; }
    </style>
</head>
<body>
    <h2>Laporan Laba</h2>
    <p>Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d-m-Y') }}</p>

    <h4>Rekapitulasi:</h4>
    <p>Total Penjualan: Rp {{ number_format($total_penjualan, 0, ',', '.') }}</p>
    <p>Total Modal: Rp {{ number_format($total_modal, 0, ',', '.') }}</p>
    <p><strong>Laba Bersih: Rp {{ number_format($total_laba, 0, ',', '.') }}</strong></p>

    <h4>Detail Penjualan:</h4>
    <table>
        <thead>
            <tr>
                <th>No. Invoice</th>
                <th>Tanggal</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualans as $item)
                <tr>
                    <td>{{ $item->no_invoice }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
