<h2>Laporan Harian - {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</h2>

<p>Total Penjualan: Rp {{ number_format($total_penjualan, 0, ',', '.') }}</p>
<p>Total Modal: Rp {{ number_format($total_modal, 0, ',', '.') }}</p>
<p><strong>Laba Bersih: Rp {{ number_format($total_laba, 0, ',', '.') }}</strong></p>

<hr>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
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
