<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Barang - Toko Haryadi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            table {
                border-collapse: collapse;
                width: 100%;
                margin-top: 1rem;
            }
            th, td {
                border: 1px solid #000;
                padding: 8px;
                font-size: 12px;
            }
            .no-print {
                display: none;
            }
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 1rem;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 13px;
        }
    </style>
</head>
<body class="bg-white text-black text-sm p-8" onload="window.print()">

    <!-- Header -->
    <div class="flex items-center justify-between border-b pb-4 mb-6">
        <div class="flex items-center gap-4">
            {{-- <img src="{{ public_path('logo.png') }}" alt="Logo" class="h-14 w-14 object-cover"> --}}
            <div>
                <h1 class="text-2xl font-bold">Toko Elektronik Haryadi</h1>
                <p class="text-sm text-gray-600">Jl. Contoh No. 123, Kota Anda</p>
            </div>
        </div>
        <div class="text-right">
            <h2 class="text-xl font-semibold">Laporan Stok Barang</h2>
            <p class="text-sm text-gray-600">Tanggal Cetak: {{ now()->format('d F Y, H:i') }}</p>
        </div>
    </div>

    <!-- Tabel Laporan -->
    <table>
        <thead class="bg-gray-200">
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Total Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangs as $index => $barang)
                <tr class="hover:bg-gray-50">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                    <td class="text-right">{{ $barang->stok }}</td>
                    <td class="text-right">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($barang->stok * $barang->harga_beli, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="bg-gray-100 font-bold">
                <td colspan="6" class="text-right">💰 Total Nilai Seluruh Stok</td>
                <td class="text-right">
                    Rp {{ number_format($total_nilai_stok, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Tanda Tangan -->
    <div class="flex justify-end mt-16">
        <div class="text-center">
            <p>Mengetahui,</p>
        </br>
            <p class="mt-20 font-semibold underline">_______________________</p>
            <p class="text-sm text-gray-600">Pimpinan</p>
        </div>
    </div>

</body>
</html>
