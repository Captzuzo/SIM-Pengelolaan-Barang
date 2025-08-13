<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan Tahun {{ $tahun }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; font-size: 12px; text-align: center; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan Tahun {{ $tahun }}</h2>
    <p>Total Penjualan: Rp {{ number_format($total_penjualan, 0, ',', '.') }}</p>
    <p>Total Modal: Rp {{ number_format($total_modal, 0, ',', '.') }}</p>
    <p>Laba Bersih: Rp {{ number_format($total_laba, 0, ',', '.') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Invoice</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Subtotal</th>
                <th>Bayar</th>
                <th>Piutang</th>
                <th>Kembalian</th>
                <th>Laba</th>
            </tr>
        </thead>
        <tbody>
             @php
                $totalSubtotal = 0;
                $totalBayar = 0;
                $totalPiutang = 0;
                $totalKembalian = 0;
            @endphp
            @foreach($penjualans as $penjualan)
                 @foreach ($penjualan->detail as $detail)
                    @php
                        $totalSubtotal += $detail->subtotal;
                        $totalBayar += $penjualan->bayar;
                        $totalPiutang += $penjualan->sisa;
                        $totalKembalian += $penjualan->kembalian;
                    @endphp
                <tr>
                    <td class="border px-2 py-1">{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                    <td>{{ $penjualan->no_invoice }}</td>
                    <td>{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $detail->barang->nama_barang ?? 'Barang dihapus' }}</td>
                    <td>{{ $detail->qty }}</td>
                    <td>Rp {{ number_format($detail->barang->harga_beli ?? 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->barang->harga_jual ?? 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($penjualan->sisa, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</td>
                    @if ($loop->first)
                        <td rowspan="{{ $penjualan->detail->count() }}">Rp {{ number_format($penjualan->laba, 0, ',', '.') }}</td>
                    @endif
                </tr>
                @endforeach
            @endforeach
            <tr class="total-row">
                <td colspan="6" class="text-left">Total Keseluruhan</td>
                <td>Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalPiutang, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalKembalian, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($total_laba, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
