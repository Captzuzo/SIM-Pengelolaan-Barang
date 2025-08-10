<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $pembelian->no_invoice }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Invoice Barang Beli</h2>
    <p><strong>No Invoice:</strong> {{ $pembelian->no_invoice }}</p>
    {{-- <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</p> --}}
    <p><strong>Tanggal Beli:</strong> {{ \Carbon\Carbon::parse($pembelian->tanggal_beli)}}</p>
    <p><strong>Supplier:</strong> {{ $pembelian->supplier->nama }}</p>
    <p><strong>Kasir:</strong> {{ $pembelian->kasir->name }}</p>

    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga Beli</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($pembelian->detailBarangBeli as $detail)
                @php
                    $subtotal = $detail->stok * $detail->harga_satuan;
                    $total += $subtotal;
                @endphp
                <tr>
                    <td>{{ $detail->barang->nama_barang }}</td>
                    <td>{{ $detail->stok }}</td>
                    <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" align="right"><strong>Total</strong></td>
                <td><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
