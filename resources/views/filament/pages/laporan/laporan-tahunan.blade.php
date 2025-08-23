<x-filament::page>
    {{-- Form Filter --}}
    <form wire:submit.prevent="generate" class="space-y-4 bg-dark p-4 rounded-lg shadow-sm">
        <div class="grid grid-cols-2 gap-4">
            {{ $this->form }}
        </div>

        <div class="flex items-center gap-3">
            <x-filament::button type="submit" icon="heroicon-o-magnifying-glass">
                Tampilkan Laporan
            </x-filament::button>

            @if (!empty($data['penjualans']))
                <a href="{{ route('laporan-tahunan.cetak', ['tahun' => $tahun]) }}" target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg shadow hover:bg-primary-700 transition">
                    <x-filament::icon name="heroicon-o-printer" class="w-5 h-5 mr-2" />
                    Cetak PDF
                </a>
            @endif
        </div>

        {{-- Loading --}}
        <div wire:loading class="flex items-center space-x-2 text-gray-600">
            <svg class="animate-spin h-5 w-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
            </svg>
            <span>Memuat laporan...</span>
        </div>
    </form>

    @if (!empty($data['penjualans']) && $data['penjualans']->count())
        {{-- Rekap --}}
        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg shadow-sm">
                <h3 class="text-sm font-medium text-green-800">Total Penjualan</h3>
                <p class="mt-1 text-xl font-bold text-green-900">Rp
                    {{ number_format($data['total_penjualan'], 0, ',', '.') }}</p>
            </div>
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg shadow-sm">
                <h3 class="text-sm font-medium text-blue-800">Total Modal</h3>
                <p class="mt-1 text-xl font-bold text-blue-900">Rp
                    {{ number_format($data['total_modal'], 0, ',', '.') }}</p>
            </div>
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg shadow-sm">
                <h3 class="text-sm font-medium text-yellow-800">Laba Bersih</h3>
                <p class="mt-1 text-xl font-bold text-yellow-900">Rp
                    {{ number_format($data['total_laba'], 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Detail --}}
        <div class="mt-8 bg-dark p-4 rounded-lg shadow-sm border">
            <h2 class="text-lg font-bold mb-4">Detail Penjualan</h2>
            {{-- Filter --}}
            {{-- <div class="flex gap-2 mb-4">
                <button wire:click="$set('filterStatus', null)"
                    class="px-4 py-2 rounded-lg border {{ $filterStatus === null ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
                    Semua
                </button>
                <button wire:click="$set('filterStatus', 'lunas')"
                    class="px-4 py-2 rounded-lg border {{ $filterStatus === 'lunas' ? 'bg-green-600 text-white' : 'bg-gray-200' }}">
                    Lunas
                </button>
                <button wire:click="$set('filterStatus', 'belum')"
                    class="px-4 py-2 rounded-lg border {{ $filterStatus === 'belum' ? 'bg-red-600 text-white' : 'bg-gray-200' }}">
                    Belum Bayar
                </button>
            </div> --}}
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-dark-100 text-dark-800">
                            <th class="border px-2 py-2">No. Invoice</th>
                            <th class="border px-2 py-2">Tanggal</th>
                            <th class="border px-2 py-2">Nama Barang</th>
                            <th class="border px-2 py-2">Qty</th>
                            <th class="border px-2 py-2">Harga Beli</th>
                            <th class="border px-2 py-2">Harga Jual</th>
                            <th class="border px-2 py-2">Subtotal</th>
                            <th class="border px-2 py-2">Piutang</th>
                            <th class="border px-2 py-2">Laba</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalQty = 0;
                            $totalBarangBeli = 0;
                            $totalBarangJual = 0;
                            $totalSubtotal = 0;
                            $totalBayar = 0;
                            $totalPiutang = 0;
                            $totalKembalian = 0;
                        @endphp

                        @foreach ($data['penjualans'] as $item)
                            @foreach ($item->detail as $detail)
                                @php
                                    $totalQty += $detail->qty;
                                    $totalBarangBeli += $detail->barang->harga_beli * $detail->qty;
                                    $totalBarangJual += $detail->barang->harga_jual * $detail->qty;
                                    $totalSubtotal += $detail->subtotal;
                                    $totalBayar += $item->bayar;
                                    $totalPiutang += $item->sisa;
                                    $totalKembalian += $item->kembalian;
                                @endphp
                                <tr class="text-center">
                                    <td class="border px-2 py-1">{{ $item->no_invoice }}</td>
                                    <td class="border px-2 py-1">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                    <td class="border px-2 py-1">{{ $detail->barang->nama_barang ?? 'Barang dihapus' }}
                                    </td>
                                    <td class="border px-2 py-1">{{ $detail->qty }}</td>
                                    <td class="border px-2 py-1">Rp
                                        {{ number_format($detail->barang->harga_beli, 0, ',', '.') }}</td>
                                    <td class="border px-2 py-1">Rp
                                        {{ number_format($detail->barang->harga_jual, 0, ',', '.') }}</td>
                                    <td class="border px-2 py-1">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                    <td class="border px-2 py-1">Rp {{ number_format($item->sisa, 0, ',', '.') }}</td>
                                    @if ($loop->first)
                                        <td class="border px-2 py-1 text-green-600 font-semibold"
                                            rowspan="{{ $item->detail->count() }}">
                                            Rp {{ number_format($item->laba, 0, ',', '.') }}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach

                        <tr class="bg-dark-100 font-bold text-center">
                            <td colspan="3" class="border px-2 py-1 text-right">Total</td>
                            <td class="border px-2 py-1">{{ number_format($totalQty, 0, ',', '.') }}</td>
                            <td class="border px-2 py-1">Rp {{ number_format($totalBarangBeli, 0, ',', '.') }}</td>
                            <td class="border px-2 py-1">Rp {{ number_format($totalBarangJual, 0, ',', '.') }}</td>
                            {{-- <td class="border px-2 py-1"></td>
                            <td class="border px-2 py-1"></td> --}}
                            <td class="border px-2 py-1">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</td>
                            <td class="border px-2 py-1">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</td>
                            <td class="border px-2 py-1">Rp {{ number_format($data['total_laba'], 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-filament::page>


{{-- @if (!empty($data['penjualans']))
    <a href="{{ route('laporan-tahunan.cetak', ['tahun' => $tahun]) }}" target="_blank"
        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg shadow hover:bg-primary-700 transition">
        <x-filament::icon name="printer" class="w-5 h-5 mr-2" />
        Cetak PDF
    </a>
@endif --}}
