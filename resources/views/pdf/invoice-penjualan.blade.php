<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        .text-right { text-align: right; }
        .no-border { border: none !important; }
    </style>
</head>
<body>
    <h2>INVOICE PENJUALAN</h2>
    <p><strong>No. Invoice:</strong> {{ $penjualan->no_invoice }}</p>
    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</p>
    <p><strong>Pelanggan:</strong> {{ $penjualan->pelanggan->nama ?? '-' }}</p>
    <p><strong>Kasir:</strong> {{ $penjualan->kasir->name ?? '-' }}</p>

    <hr>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualan->details as $i => $detail)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $detail->qty }}</td>
                    <td class="text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <table class="no-border">
        <tr>
            <td class="no-border text-right"><strong>Total:</strong></td>
            <td class="no-border text-right">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="no-border text-right"><strong>Bayar:</strong></td>
            <td class="no-border text-right">Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="no-border text-right"><strong>Sisa:</strong></td>
            <td class="no-border text-right">Rp {{ number_format($penjualan->sisa, 0, ',', '.') }}</td>
        </tr>
    </table>

    <p style="margin-top: 30px;">Terima kasih telah berbelanja!</p>
</body>
</html>
