<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laba</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        h2, h4 {
            margin: 0;
            padding-top: 10px;
        }
        .summary {
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <h2>Laporan Laba</h2>
    <p>Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d-m-Y') }}</p>

    <div class="summary">
        <h4>Rekapitulasi:</h4>
        <p>Total Penjualan: <strong>Rp {{ number_format($total_penjualan, 0, ',', '.') }}</strong></p>
        <p>Total Modal: <strong>Rp {{ number_format($total_modal, 0, ',', '.') }}</strong></p>
        <p>Laba Bersih: <strong>Rp {{ number_format($total_laba, 0, ',', '.') }}</strong></p>
    </div>

    <div class="detail">
        <h4>Detail Penjualan:</h4>
        <table>
            <thead>
                <tr>
                    <th>No. Invoice</th>
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penjualans as $penjualan)
                    @foreach ($penjualan->details as $detail)
                        <tr>
                            <td>{{ $penjualan->no_invoice }}</td>
                            <td>{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                            <td>Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endforeach
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total</strong></td>
                    <td><strong>Rp {{ number_format($total_penjualan, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
</html>
