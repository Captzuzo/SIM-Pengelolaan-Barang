<x-filament::page>
    <form wire:submit.prevent="generate" class="space-y-4">
        {{ $this->form }}

        <x-filament::button type="submit">
            Tampilkan Laporan
        </x-filament::button>

        {{-- @if ($tanggal && !empty($data['penjualans']))
            <a 
                href="{{ route('laporan-laba.cetak', ['tanggalMulai' => $tanggalMulai, 'tanggalSelesai' => $tanggalSelesai]) }}" 
                target="_blank" 
                class="inline-flex items-center px-4 py-2 bg-danger-600 text-dark rounded-md hover:bg-danger-700 transition">
                Cetak PDF
            </a>
        @endif --}}

        <div class="mt-4">
        <div wire:loading.remove>
            @if ($tanggalMulai && $tanggalSelesai && !empty($data['penjualans']))
                <a href="{{ route('laporan-harian.cetak', ['tanggalMulai' => $tanggalMulai, 'tanggalSelesai' => $tanggalSelesai]) }}" target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg shadow hover:bg-primary-700 transition">
                    <x-filament::icon name="printer" class="w-5 h-5 mr-2" />
                    Cetak PDF
                </a>
            @endif
        </div>
        <div wire:loading class="flex items-center space-x-2 text-gray-600">
            <svg class="animate-spin h-5 w-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <span>Memuat laporan...</span>
        </div>
    </div>

        {{-- @if ($tanggalMulai && $tanggalSelesai && !empty($data['penjualans']))
            <a href="{{ route('laporan-harian.cetak', ['tanggalMulai' => $tanggalMulai, 'tanggalSelesai' => $tanggalSelesai]) }}" 
            target="_blank" 
            class="inline-flex items-center px-4 py-2 bg-danger-600 text-dark rounded-md hover:bg-danger-700 transition">
            <!-- Icon print -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18h12v4H6v-4zM6 14h12v4H6v-4z" />
            </svg>

                Cetak PDF
            </a>
        @endif --}}
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
        {{-- <div class="mt-6">
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
                        <tr>
                            <td colspan="5" class="border px-2 py-1 text-right font-bold">Total</td>
                            <td class="border px-2 py-1 font-bold">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border px-2 py-1 text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div> --}}

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
                        <th class="border px-2 py-1">Harga Beli</th>
                        <th class="border px-2 py-1">Harga Jual</th>
                        <th class="border px-2 py-1">Subtotal</th>
                        <th class="border px-2 py-1">Bayar</th>
                        <th class="border px-2 py-1">Piutang</th>
                        <th class="border px-2 py-1">Kembalian</th>
                        <th class="border px-2 py-1">Laba</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalSubtotal = 0;
                        $totalBayar = 0;
                        $totalPiutang = 0;
                        $totalKembalian = 0;
                        $totalLaba = 0;
                    @endphp

                    @forelse ($data['penjualans'] as $item)
                        @php
                            $modalTransaksi = 0;
                        @endphp
                        @foreach ($item->detail as $detail)
                            @php
                                $totalSubtotal += $detail->subtotal;
                                $totalBayar += $item->bayar;
                                $totalPiutang += $item->piutang;
                                $totalKembalian += $item->kembalian;
                            @endphp
                            <tr class="text-center">
                                <td class="border px-2 py-1">{{ $item->no_invoice }}</td>
                                <td class="border px-2 py-1">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                <td class="border px-2 py-1">{{ $detail->barang->nama_barang ?? 'Barang dihapus' }}</td>
                                <td class="border px-2 py-1">{{ $detail->qty }}</td>
                                <td class="border px-2 py-1">Rp {{ number_format($detail->barang->harga_beli, 0, ',', '.') }}</td>
                                <td class="border px-2 py-1">Rp {{ number_format($detail->barang->harga_jual, 0, ',', '.') }}</td>
                                {{-- <td class="border px-2 py-1">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td> --}}
                                <td class="border px-2 py-1">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                <td class="border px-2 py-1">Rp {{ number_format($item->bayar, 0, ',', '.') }}</td>
                                <td class="border px-2 py-1">Rp {{ number_format($item->sisa, 0, ',', '.') }}</td>
                                <td class="border px-2 py-1">Rp {{ number_format($item->kembalian, 0, ',', '.') }}</td>
@if ($loop->first)
    <td class="border px-2 py-1 text-green-600" rowspan="{{ $item->detail->count() }}">
        Rp {{ number_format($item->laba, 0, ',', '.') }}
    </td>
@endif
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="9" class="border px-2 py-1 text-center">Tidak ada data</td>
                        </tr>
                    @endforelse

                    {{-- Total keseluruhan --}}
                    <tr class="bg-gray-200 font-bold text-center">
                        <td colspan="6" class="border px-2 py-1 text-right">Total Keseluruhan</td>
                        <td class="border px-2 py-1">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</td>
                        <td class="border px-2 py-1">Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
                        <td class="border px-2 py-1">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</td>
                        <td class="border px-2 py-1">Rp {{ number_format($totalKembalian, 0, ',', '.') }}</td>
                        <td class="border px-2 py-1">Rp {{ number_format($data['total_laba'], 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    @endif
</x-filament::page>
