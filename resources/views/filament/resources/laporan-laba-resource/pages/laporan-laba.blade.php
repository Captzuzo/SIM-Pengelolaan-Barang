{{-- <x-filament::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{ $this->form }}
            <div class="flex items-end">
                <x-filament::button wire:click="generate" color="warning">
                    Tampilkan Laporan
                </x-filament::button>
            </div>
        </div>

        @if($data['total_penjualan'] > 0)
            <x-filament::card>
                <h3 class="text-lg font-bold mb-4 text-white">Rekapitulasi</h3>
                <div class="text-white space-y-1">
                    <p>Total Penjualan: <span class="font-semibold text-primary-500">Rp {{ number_format($data['total_penjualan'], 0, ',', '.') }}</span></p>
                    <p>Total Modal: <span class="font-semibold text-danger-500">Rp {{ number_format($data['total_modal'], 0, ',', '.') }}</span></p>
                    <p class="text-lg">Laba Bersih: <span class="font-bold text-success-500">Rp {{ number_format($data['total_laba'], 0, ',', '.') }}</span></p>
                </div>
            </x-filament::card>

            <x-filament::card class="mt-6">
                <h3 class="text-lg font-bold mb-4 text-white">Detail Penjualan</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-white border border-white">
                        <thead class="bg-gray-800 text-white uppercase text-xs">
                            <tr>
                                <th class="px-4 py-2 border">No. Invoice</th>
                                <th class="px-4 py-2 border">Tanggal</th>
                                <th class="px-4 py-2 border">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($data['penjualans'] as $penjualan)
                                <tr class="hover:bg-gray-700">
                                    <td class="px-4 py-2 border">{{ $penjualan->no_invoice }}</td>
                                    <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</td>
                                    <td class="px-4 py-2 border">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach

                            @php
                                $totalDariTabel = collect($data['penjualans'])->sum('total');
                            @endphp
                            <tr class="bg-gray-800 font-bold">
                                <td class="px-4 py-2 border text-right" colspan="2">Jumlah Total</td>
                                <td class="px-4 py-2 border text-white">Rp {{ number_format($totalDariTabel, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-filament::card>
        @else
            <x-filament::card class="mt-6 text-white">
                <p class="text-center text-gray-400">Belum ada data penjualan pada rentang tanggal yang dipilih.</p>
            </x-filament::card>
        @endif
    </div>
</x-filament::page> --}}
