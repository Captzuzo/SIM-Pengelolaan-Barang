<x-filament::page>
    <div class="space-y-6">
        <x-filament::card class="bg-gray-900/60 backdrop-blur-md shadow-xl border border-gray-700 text-white">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-3xl font-extrabold tracking-tight flex items-center gap-2">
                    📦 <span>Laporan Stok Barang</span>
                </h3>
                <span class="text-sm text-gray-400 italic">🕒 {{ now()->format('d M Y, H:i') }}</span>
                <a 
                    href="{{ route('laporan-stok.cetak') }}" 
                    target="_blank" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white font-medium rounded-md hover:bg-red-700 transition duration-200"
                >
                    <x-heroicon-o-printer class="w-5 h-5" />
                    Cetak PDF
                </a>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-700">
                <table class="min-w-full text-sm text-white">
                    <thead class="bg-gradient-to-r from-gray-800 to-gray-700 text-xs uppercase tracking-wider text-gray-300">
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
                        @foreach($barangs as $index => $barang)
                            <tr class="hover:bg-gray-800/70 transition duration-200 ease-in-out">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 font-semibold text-blue-300">{{ $barang->nama_barang }}</td>
                                <td class="px-4 py-2 text-gray-300">{{ $barang->kategori->nama_kategori ?? '-' }}</td>
                                <td class="px-4 py-2 text-right">{{ $barang->stok }}</td>
                                <td class="px-4 py-2 text-right text-green-400">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right text-yellow-400">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right text-white">Rp {{ number_format($barang->stok * $barang->harga_beli, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach

                        {{-- <tr class="bg-gradient-to-r from-gray-800 to-gray-700 text-white font-bold">
                            <td class="px-4 py-4 text-right" colspan="6">
                                🧮 Jumlah Total Nilai Stok
                            </td>
                            <td class="px-4 py-4 text-right text-amber-400 text-lg">
                                Rp {{ number_format($data['total_nilai_stok'], 0, ',', '.') }}
                            </td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </x-filament::card>
    </div>
</x-filament::page>
