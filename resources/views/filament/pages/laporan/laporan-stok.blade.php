<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Total Nilai Stok --}}
        {{-- <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                    <x-filament::icon name="lucide-box-dollar" /> Total Stok

                </h2>
            </div>
            <p class="text-3xl font-bold text-primary-600 dark:text-primary-500">
                Rp {{ number_format($stok, 0, ',', '.') }}
            </p>
        </div> --}}

        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                    <x-filament::icon name="lucide-box-dollar" /> Total Nilai Stok

                </h2>
            </div>
            <p class="text-3xl font-bold text-primary-600 dark:text-primary-500">
                Rp {{ number_format($totalNilaiStok, 0, ',', '.') }}
            </p>
        </div>

        {{-- Barang Terlaris --}}
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                <x-filament::icon name="lucide-star" />Barang Terlaris
            </h2>
            <ul class="space-y-1 text-gray-700 dark:text-gray-200">
                @forelse ($barangTerlaris as $barang)
                    <li>🔹 {{ $barang['nama_barang'] }} – <strong>{{ $barang['qty'] }}</strong> terjual</li>
                @empty
                    <li class="italic text-sm text-gray-500">Tidak ada data.</li>
                @endforelse
            </ul>
        </div>

        {{-- Barang Hampir Habis --}}
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6 col-span-1 md:col-span-2">
            <h2 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">
                <x-filament::icon name="lucide-alert-circle" />Barang Hampir Habis
            </h2>
            <ul class="space-y-1">
                @forelse ($barangHampirHabis as $barang)
                    <li class="flex items-center justify-between bg-red-50 dark:bg-red-900/30 px-4 py-2 rounded-md">
                        <span class="text-gray-800 dark:text-gray-200">
                            {{ $barang['nama_barang'] }}
                        </span>
                        <span class="text-sm bg-red-600 text-white px-2 py-1 rounded-full">Stok:
                            {{ $barang['stok'] }}</span>
                    </li>
                @empty
                    <li class="italic text-sm text-gray-500">Semua stok aman.</li>
                @endforelse
            </ul>
        </div>

        {{-- Daftar Semua Barang --}}
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6 w-full col-span-full">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                <x-filament::icon name="lucide-archive" />Daftar Semua Barang
            </h2>

            <div class="overflow-x-auto">
                {{-- <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"> --}}
                <table class="w-full divide-y divide-dark-200 dark:divide-dark-700">
                    <thead class="bg-gray-100 dark:bg-dark-700">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Nama
                            </th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Kategori</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Harga
                                Beli</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Stok
                            </th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Subtotal</th>
                        </tr>
                    </thead>
                    {{-- <tbody class="divide-y divide-gray-200 dark:divide-gray-700"> --}}
                    @foreach ($barangs as $barang)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40">
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">
                                {{ $barang['nama_barang'] }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">
                                {{ $barang['kategori']['kode_kategori'] ?? '-' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">
                                Rp {{ number_format($barang['harga_beli'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">
                                {{ $barang['stok'] }}
                            </td>
                            <td class="px-4 py-2 text-sm font-semibold text-primary-700 dark:text-primary-400">
                                Rp {{ number_format($barang['stok'] * $barang['harga_beli'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-filament::page>
