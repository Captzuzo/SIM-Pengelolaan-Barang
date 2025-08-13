<x-filament::page>
    <form wire:submit.prevent="generate" class="space-y-4">
        {{ $this->form }}

        <x-filament::button type="submit">Tampilkan Laporan</x-filament::button>

    {{-- Tombol Cetak PDF --}}
    <div class="mt-4">
        <div wire:loading.remove>
            @if(!empty($data['penjualans']))
                <a href="{{ route('laporan-tahunan.cetak', ['tahun' => $tahun]) }}" target="_blank"
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
    </form>

    <div wire:loading.remove>
    @if (!empty($data['penjualans']))
    <div class="mt-6 space-y-2">
        <h2 class="text-lg font-bold">Detail Penjualan Tahun {{ $tahun }}</h2>
        <p>Total Penjualan: Rp {{ number_format($data['total_penjualan'], 0, ',', '.') }}</p>
        <p>Total Modal: Rp {{ number_format($data['total_modal'], 0, ',', '.') }}</p>
        <p><strong>Laba Bersih: Rp {{ number_format($data['total_laba'], 0, ',', '.') }}</strong></p>
    </div>

    <div class="mt-6 overflow-x-auto">
        <table class="w-full table-auto border border-collapse">
            <thead class="bg-gray-200">
                <tr class="text-center">
                    <th class="border px-2 py-1">No</th>
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
                @foreach ($data['penjualans'] as $penjualan)
                     @php
                            $modalTransaksi = 0;
                        @endphp
                        @foreach ($penjualan->detail as $detail)
                            @php
                                $totalSubtotal += $detail->subtotal;
                                $totalBayar += $penjualan->bayar;
                                $totalPiutang += $penjualan->sisa;
                                $totalKembalian += $penjualan->kembalian;
                            @endphp
                        <tr class="text-center">
                            <td class="border px-2 py-1">{{ $loop->parent->iteration }}</td>
                            <td class="border px-2 py-1">{{ $penjualan->no_invoice }}</td>
                            <td class="border px-2 py-1">{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</td>
                            <td class="border px-2 py-1 text-left">{{ $detail->barang->nama_barang ?? 'Barang dihapus' }}</td>
                            <td class="border px-2 py-1">{{ $detail->qty }}</td>
                            <td class="border px-2 py-1">Rp {{ number_format($detail->barang->harga_beli ?? 0, 0, ',', '.') }}</td>
                            <td class="border px-2 py-1">Rp {{ number_format($detail->barang->harga_jual ?? 0, 0, ',', '.') }}</td>
                            <td class="border px-2 py-1">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            <td class="border px-2 py-1">Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</td>
                            <td class="border px-2 py-1">Rp {{ number_format($penjualan->sisa, 0, ',', '.') }}</td>
                            <td class="border px-2 py-1">Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</td>
                            @if ($loop->first)
                                <td class="border px-2 py-1" rowspan="{{ $penjualan->detail->count() }}">
                                    Rp {{ number_format($penjualan->laba, 0, ',', '.') }}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach

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
    </div>
</x-filament::page>
