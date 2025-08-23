<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan Tahun {{ $tahun }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 2px 0;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 11px;
            text-align: center;
        }

        th {
            background-color: #222;
            color: #fff;
        }

        .summary {
            margin-top: 20px;
        }

        .summary p {
            margin: 4px 0;
            font-weight: bold;
        }

        .total-row {
            font-weight: bold;
            background: #f2f2f2;
        }

        .signature {
            margin-top: 40px;
            width: 100%;
        }

        .signature td {
            border: none;
            text-align: center;
        }
    </style>
</head>

<body>
    {{-- HEADER --}}
    <div class="header">
        <h2>LAPORAN PENJUALAN TAHUN {{ $tahun }}</h2>
        @php
            use Carbon\Carbon;
            Carbon::setLocale('id');
        @endphp

        <p> Tanggal : {{ Carbon::now()->translatedFormat('d F Y') }}</p>
    </div>

    {{-- RINGKASAN --}}
    <div class="summary">
        <p>Total Penjualan : Rp {{ number_format($total_penjualan, 0, ',', '.') }}</p>
        <p>Total Modal : Rp {{ number_format($total_modal, 0, ',', '.') }}</p>
        <p>Laba Bersih : Rp {{ number_format($total_laba, 0, ',', '.') }}</p>
    </div>

    {{-- TABEL DETAIL --}}
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
                $totalQty = 0;
                $totalBarangBeli = 0;
                $totalBarangJual = 0;
                $totalSubtotal = 0;
                $totalBayar = 0;
                $totalPiutang = 0;
                $totalKembalian = 0;
            @endphp

            @foreach ($penjualans as $item)
                @foreach ($item->detail as $detail)
                    @php
                        $totalQty += $detail->qty;
                        $totalBarangBeli += $detail->barang->harga_beli * $detail->qty;
                        $totalBarangJual += $detail->barang->harga_jual * $detail->qty;
                        $totalSubtotal += $detail->subtotal;
                        $totalBayar += $item->bayar;
                        $totalPiutang += $item->sisa;
                        $totalKembalian += $item->kembalian;
                    @endphp
                    <tr>
                        <td>{{ $loop->parent->iteration }}</td>
                        <td>{{ $item->no_invoice }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td>{{ $detail->barang->nama_barang ?? 'Barang dihapus' }}</td>
                        <td>{{ $detail->qty }}</td>
                        <td>Rp {{ number_format($detail->barang->harga_beli ?? 0, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($detail->barang->harga_jual ?? 0, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->bayar, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->sisa, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->kembalian, 0, ',', '.') }}</td>
                        @if ($loop->first)
                            <td rowspan="{{ $item->detail->count() }}">
                                Rp {{ number_format($item->laba, 0, ',', '.') }}
                            </td>
                        @endif
                    </tr>
                @endforeach
            @endforeach

            {{-- TOTAL --}}
            <tr class="total-row">
                <td colspan="4" class="text-right">Total</td>
                <td class="border px-2 py-1">{{ number_format($total_Qty, 0, ',', '.') }}</td>
                <td class="border px-2 py-1">Rp {{ number_format($totalBarangBeli, 0, ',', '.') }}</td>
                <td class="border px-2 py-1">Rp {{ number_format($totalBarangJual, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalPiutang, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalKembalian, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($total_laba, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- FOOTER / TANDA TANGAN --}}
    <table class="signature">
        <tr>
            <td>Disetujui,</td>
            <td>Dibuat Oleh,</td>
        </tr>
        <tr>
            <td height="60px"></td>
            <td></td>
        </tr>
        <tr>
            <td>(_________________)</td>
            <td>(_________________)</td>
        </tr>
    </table>
</body>

</html>
