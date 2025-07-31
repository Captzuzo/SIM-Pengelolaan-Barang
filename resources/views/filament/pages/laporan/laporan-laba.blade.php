<x-filament::page>
    <form wire:submit.prevent="generate" class="space-y-4">
        {{ $this->form }}

        <x-filament::button type="submit">
            Tampilkan Laporan
        </x-filament::button>
        @if ($tanggalMulai && $tanggalSelesai)
        <a 
            href="{{ route('laporan-laba.cetak', ['tanggalMulai' => $tanggalMulai, 'tanggalSelesai' => $tanggalSelesai]) }}" 
            target="_blank" 
            class="inline-flex items-center px-4 py-2 bg-danger-600 text-white rounded-md hover:bg-danger-700 transition">
            Cetak PDF
        </a>
        @endif
    </form>

    

    @if (!empty($data['penjualans']) && is_iterable($data['penjualans']))
    <div class="mt-6 space-y-2">
        <h2 class="text-lg font-bold">Rekapitulasi</h2>
        <p>Total Penjualan: Rp {{ number_format($data['total_penjualan'], 0, ',', '.') }}</p>
        <p>Total Modal: Rp {{ number_format($data['total_modal'], 0, ',', '.') }}</p>
        <p><strong>Laba Bersih: Rp {{ number_format($data['total_laba'], 0, ',', '.') }}</strong></p>
    </div>

    <div class="mt-6">
        <h2 class="text-lg font-bold mb-2">Detail Penjualan</h2>
        <table class="w-full table-auto border">
            <thead class="bg-black-100">
                <tr>
                    <th class="border px-2 py-1">No. Invoice</th>
                    <th class="border px-2 py-1">Nama Barang</th>
                    <th class="border px-2 py-1">Tanggal</th>
                    <th class="border px-2 py-1">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['detail_penjualans'] as $item)
                <tr>
                    <td class="border px-2 py-1">{{ $item->no_invoice }}</td>
                    <td class="border px-2 py-1">{{ $item->barangs->nama_barang ?? '-' }}</td>
                    <td class="border px-2 py-1">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td class="border px-2 py-1">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach

                <tr class="bg-gray-800 font-bold">
                            <td class="px-4 py-2 border text-right" colspan="3">Jumlah Total</td>
                            <td class="px-4 py-2 border text-white">
                                Rp {{ number_format($data['total_penjualan'], 0, ',', '.') }}
                            </td>
                        </tr>
            </tbody>
        </table>
    </div>
@endif

</x-filament::page>
