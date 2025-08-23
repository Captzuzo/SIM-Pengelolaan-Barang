{{-- <!DOCTYPE html>
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
            <td class="no-border text-right"><strong>Piutang:</strong></td>
            <td class="no-border text-right">Rp {{ number_format($penjualan->sisa, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="no-border text-right"><strong>Kembalian:</strong></td>
            <td class="no-border text-right">Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="no-border text-right"><strong>Status:</strong></td>
            <td class="no-border text-right">{{ $penjualan->status_pembayaran}}</td>
        </tr>
    </table>

    <p class="mt-8 text-center">Terima kasih telah berbelanja!</p>
</body>
</html> --}}


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Invoice Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 20px;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .company {
            float: left;
            width: 50%;
        }

        .invoice-title {
            float: right;
            width: 50%;
            text-align: right;
        }

        h2 {
            margin: 0;
            text-transform: uppercase;
        }

        p {
            margin: 2px 0;
        }

        .address-block {
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .address-block .block {
            float: left;
            width: 50%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .no-border {
            border: none !important;
        }

        .summary {
            width: 40%;
            float: right;
            margin-top: 15px;
            border-collapse: collapse;
        }

        .summary td {
            border: 1px solid #000;
            padding: 6px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="clearfix">
        <div class="company">
            <strong>Optimus Cell Service & Printing</strong><br>
            RT.4/RW.3, Pasuruhan Kidul II, Pasuruhan Kidul, Kec. Jati<br>
            Kabupaten Kudus, Jawa Tengah 59349<br>
            Telp: 085155052911<br>
        </div>
        <div class="invoice-title">
            <h2>INVOICE</h2>
            <p><strong>No. Invoice:</strong> {{ $penjualan->no_invoice }}</p>
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</p>
        </div>
    </div>

    <!-- Address Section -->
    <div class="clearfix address-block">
        <div class="block">
            <p><strong>Kepada:</strong></p>
            <p>{{ $penjualan->pelanggan->nama ?? '-' }}</p>
            <p>{{ $penjualan->pelanggan->alamat_lengkap ?? '-' }}</p>
            <p>Telp: {{ $penjualan->pelanggan->no_hp ?? '-' }}</p>
        </div>
        <div class="block">
            <p><strong>Kasir:</strong></p>
            <p>{{ $penjualan->kasir->name ?? '-' }}</p>
        </div>
    </div>

    <!-- Table Items -->
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
            @foreach ($penjualan->detail as $i => $detail)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                    <td class="text-center">{{ $detail->qty }}</td>
                    <td class="text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary -->
    <table class="summary">
        <tr>
            <td class="no-border"><strong>Total</strong></td>
            <td class="no-border text-right">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="no-border"><strong>Bayar</strong></td>
            <td class="no-border text-right">Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="no-border"><strong>Piutang</strong></td>
            <td class="no-border text-right">Rp {{ number_format($penjualan->sisa, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="no-border"><strong>Kembalian</strong></td>
            <td class="no-border text-right">Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="no-border"><strong>Status Pembayaran</strong></td>
            <td class="no-border text-right">{{ ucfirst($penjualan->status_pembayaran) }}</td>
        </tr>
    </table>

    <div style="clear: both;"></div>

    <!-- Footer -->
    <p class="footer">Terima kasih telah berbelanja di toko kami!</p>

</body>

</html>
