<x-filament::page>
    <div class="space-y-6">
        <x-filament::card class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white shadow-lg border border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold">📦 Laporan Stok Barang</h3>
                <span class="text-sm text-gray-400">Terakhir diperbarui: {{ now()->format('d M Y, H:i') }}</span>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-700">
                <table class="min-w-full divide-y divide-gray-700 text-sm text-white">
                    <thead class="bg-gray-800 text-xs uppercase tracking-wider text-gray-300">
                        <tr>
                            <th class="px-4 py-3 text-left">No</th>
                            <th class="px-4 py-3 text-left">Nama Barang</th>
                            <th class="px-4 py-3 text-left">Kategori</th>
                            <th class="px-4 py-3 text-right">Stok</th>
                            <th class="px-4 py-3 text-right">Harga Beli</th>
                            <th class="px-4 py-3 text-right">Harga Jual</th>
                            <th class="px-4 py-3 text-right">Total Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($data['barangs'] as $index => $barang)
                            <tr class="hover:bg-gray-700 transition duration-200 ease-in-out">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 font-semibold">{{ $barang->nama }}</td>
                                <td class="px-4 py-2">{{ $barang->kategori->nama ?? '-' }}</td>
                                <td class="px-4 py-2 text-right">{{ $barang->stok }}</td>
                                <td class="px-4 py-2 text-right">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right">Rp {{ number_format($barang->stok * $barang->harga_beli, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach

                        {{-- Baris Total --}}
                        <tr class="bg-gray-900 text-white font-bold">
                            <td class="px-4 py-3 text-right" colspan="6">💰 Jumlah Total Nilai Stok</td>
                            <td class="px-4 py-3 text-right">
                                Rp {{ number_format($data['total_nilai_stok'], 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-filament::card>
    </div>
</x-filament::page>
