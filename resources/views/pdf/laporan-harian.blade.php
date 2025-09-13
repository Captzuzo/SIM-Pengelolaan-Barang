<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Harian</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #eee;
        }

        .text-left {
            text-align: left;
        }

        .total-row {
            font-weight: bold;
            background-color: #ddd;
        }
    </style>
</head>

<body>
    <h2>Laporan Harian</h2>
    <p>Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d-m-Y') }} s/d
        {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d-m-Y') }}</p>

    <p>Total Penjualan: Rp {{ number_format($total_penjualan, 0, ',', '.') }}</p>
    <p>Total Modal: Rp {{ number_format($total_modal, 0, ',', '.') }}</p>
    <p><strong>Laba Bersih: Rp {{ number_format($total_laba, 0, ',', '.') }}</strong></p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Invoice</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Subtotal</th>
                <th>Bayar</th>
                <th>Piutang</th>
                {{-- <th>Kembalian</th> --}}
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

            @foreach ($penjualans as $penjualan)
                @foreach ($penjualan->detail as $detail)
                    @php
                        $totalSubtotal += $detail->subtotal;
                        $totalBayar += $penjualan->bayar;
                        $totalPiutang += $penjualan->sisa;
                        $totalKembalian += $penjualan->kembalian;
                    @endphp
                    <tr>
                        {{-- <td>{{ $loop->parent->iteration }}</td> --}}
                        @if ($loop->first)
                            <td rowspan="{{ $penjualan->detail->count() }}">
                                {{ $loop->parent->iteration }}
                            </td>
                        @endif
                        {{-- <td>{{ $penjualan->no_invoice }}</td> --}}
                        @if ($loop->first)
                            <td rowspan="{{ $penjualan->detail->count() }}">
                                {{ $penjualan->no_invoice }}
                            </td>
                        @endif
                        {{-- <td>{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</td> --}}
                        @if ($loop->first)
                            <td rowspan="{{ $penjualan->detail->count() }}">
                                {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}
                            </td>
                        @endif
                        {{-- <td class="text-left">{{ $detail->barang->nama_barang ?? 'Barang dihapus' }}</td> --}}
                        @if ($loop->first)
                            <td rowspan="{{ $penjualan->detail->count() }}">
                                {{ $detail->barang->nama_barang ?? 'Barang dihapus' }}
                            </td>
                        @endif
                        {{-- <td>{{ $detail->qty }}</td> --}}
                        @if ($loop->first)
                            <td rowspan="{{ $penjualan->detail->count() }}">
                                {{ $detail->qty }}
                            </td>
                        @endif
                        {{-- <td>Rp {{ number_format($detail->barang->harga_beli, 0, ',', '.') }}</td> --}}
                        @if ($loop->first)
                            <td rowspan="{{ $penjualan->detail->count() }}">
                                {{ number_format($detail->barang->harga_beli, 0, ',', '.') }}
                            </td>
                        @endif
                        {{-- <td>Rp {{ number_format($detail->barang->harga_jual, 0, ',', '.') }}</td> --}}
                        @if ($loop->first)
                            <td rowspan="{{ $penjualan->detail->count() }}">
                                {{ number_format($detail->barang->harga_jual, 0, ',', '.') }}
                            </td>
                        @endif
                        {{-- <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td> --}}
                        @if ($loop->first)
                            <td rowspan="{{ $penjualan->detail->count() }}">
                                {{ number_format($detail->subtotal, 0, ',', '.') }}
                            </td>
                        @endif
                        {{-- <td>Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</td> --}}
                        @if ($loop->first)
                            <td rowspan="{{ $penjualan->detail->count() }}">
                                {{ number_format($penjualan->bayar, 0, ',', '.') }}
                            </td>
                        @endif
                        {{-- <td>Rp {{ number_format($penjualan->sisa, 0, ',', '.') }}</td> --}}
                        @if ($loop->first)
                            <td rowspan="{{ $penjualan->detail->count() }}">
                                {{ number_format($penjualan->sisa, 0, ',', '.') }}
                            </td>
                        @endif
                        {{-- <td>Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</td> --}}
                        @if ($loop->first)
                            <td rowspan="{{ $penjualan->detail->count() }}">Rp
                                {{ number_format($penjualan->laba, 0, ',', '.') }}</td>
                        @endif
                    </tr>
                @endforeach
            @endforeach

            <tr class="total-row">
                <td colspan="3" class="text-left">Total</td>
                <td>Rp {{ number_format($totalQty, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalBarangBeli, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalBarangJual, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalPiutang, 0, ',', '.') }}</td>
                {{-- <td>Rp {{ number_format($totalKembalian, 0, ',', '.') }}</td> --}}
                <td>Rp {{ number_format($total_laba, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
