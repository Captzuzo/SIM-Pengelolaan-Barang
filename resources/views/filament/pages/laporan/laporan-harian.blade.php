<x-filament::page>
    <form wire:submit.prevent="generate" class="space-y-4">
        {{ $this->form }}

        <x-filament::button type="submit">
            Tampilkan Laporan
        </x-filament::button>

        @if ($tanggal && !empty($data['penjualans']))
            <a 
                href="{{ route('laporan-harian.cetak', ['tanggal' => $tanggal]) }}" 
                target="_blank" 
                class="inline-flex items-center px-4 py-2 bg-danger-600 text-dark rounded-md hover:bg-danger-700 transition">
                Cetak PDF
            </a>
        @endif
    </form>

    @if (!empty($data['penjualans']))
        {{-- Rekapitulasi --}}
        <div class="mt-6 space-y-2">
            <h2 class="text-lg font-bold">Rekapitulasi</h2>
            <p>Total Penjualan: Rp {{ number_format($data['total_penjualan'], 0, ',', '.') }}</p>
            <p>Total Modal: Rp {{ number_format($data['total_modal'], 0, ',', '.') }}</p>
            <p><strong>Laba Bersih: Rp {{ number_format($data['total_laba'], 0, ',', '.') }}</strong></p>
        </div>

        {{-- Detail Penjualan --}}
        <div class="mt-6">
            <h2 class="text-lg font-bold mb-2">Detail Penjualan</h2>
            <table class="w-full table-auto border-collapse border">
                <thead class="bg-dark-100">
                    <tr>
                        <th class="border px-2 py-1">No. Invoice</th>
                        <th class="border px-2 py-1">Tanggal</th>
                        <th class="border px-2 py-1">Nama Barang</th>
                        <th class="border px-2 py-1">Qty</th>
                        <th class="border px-2 py-1">Harga Satuan</th>
                        <th class="border px-2 py-1">Subtotal</th>
                        <th class="border px-2 py-1">Bayar</th>
                        <th class="border px-2 py-1">Piutang</th>
                        <th class="border px-2 py-1">Kembalian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data['penjualans'] as $item)
                        @foreach ($item->detail as $detail)
                            <tr class="text-center">
                                <td class="border px-2 py-1">{{ $item->no_invoice }}</td>
                                <td class="border px-2 py-1">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                <td class="border px-2 py-1">{{ $detail->barang->nama_barang ?? 'Barang dihapus' }}</td>
                                <td class="border px-2 py-1">{{ $detail->qty }}</td>
                                <td class="border px-2 py-1">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                <td class="border px-2 py-1">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                <td class="border px-2 py-1">Rp {{ number_format($detail->bayar, 0, ',', '.') }}</td>
                                <td class="border px-2 py-1">Rp {{ number_format($detail->piutang, 0, ',', '.') }}</td>
                                <td class="border px-2 py-1">Rp {{ number_format($detail->kembalian, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach

                        {{-- Total tiap invoice --}}
                        <tr>
                            <td colspan="5" class="border px-2 py-1 text-right font-bold">Total Invoice</td>
                            <td class="border px-2 py-1 font-bold">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border px-2 py-1 text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</x-filament::page>
