<?php

namespace App\Filament\Pages\Laporan;

use App\Models\Barang;
use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class LaporanTahunanPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';
    protected static ?string $title = 'Laporan Tahunan';
    protected static ?string $slug = 'laporan-tahunan';
    protected static ?int $navigationSort = 23;

    protected static string $view = 'filament.pages.laporan.laporan-tahunan';

    public ?string $tahun = null;

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        if ($user->hasRole('Admin')) {
            return true;
        }

        if ($user->hasPermissionTo('View Laporan Tahunan')) {
            return true;
        }

        return false;
    }

    protected function getFormSchema(): array
    {
        $tahunSekarang = date('Y');
        $tahunMulai = $tahunSekarang - 5; // 5 tahun terakhir

        return [
            Select::make('tahun')
                ->label('Pilih Tahun')
                ->options(array_combine(range($tahunSekarang, $tahunMulai), range($tahunSekarang, $tahunMulai)))
                ->required(),
        ];
    }

    // public function generate()
    // {
    //     $this->validate([
    //         'tahun' => 'required|digits:4',
    //     ]);

    //     // $penjualans = Penjualan::with('detail.barang')
    //     //     ->whereYear('tanggal', $this->tahun)
    //     //     ->orderBy('tanggal', 'asc')
    //     //     ->get();

    //     // $penjualans = DetailPenjualan::with('barang')
    //     //     ->selectRaw('barang_id, SUM(qty) as total_terjual')
    //     //     ->groupBy('barang_id')
    //     //     ->orderByDesc('total_terjual')
    //     //     ->first();

    //     // $totalPenjualan = $penjualans->sum('total');

    //     $penjualans = DetailPenjualan::with('barang')
    //     ->whereHas('penjualan', fn($q) => $q->whereYear('tanggal', $this->tahun))
    //     ->selectRaw('barang_id, SUM(qty) as total_terjual, SUM(subtotal) as total_subtotal')
    //     ->groupBy('barang_id')
    //     ->get();

    //     $totalPenjualan = $penjualans->sum('total_subtotal');

    //     $barang = Barang::all();

    //     // $totalPenjualan = $penjualans->sum('total'); // omzet
    //     $totalModal     = 0;
    //     $totalLaba      = 0;
    //     // $totalBarangTerjual = 0;
    //     $totalBarangTerjual += $penjualans->barang_terjual;
    //     $totalPiutang   = 0;

    //     foreach ($penjualans as $penjualan) {
    //         $penjualan->modal = 0;
    //         $penjualan->barang_terjual = 0;

    //         foreach ($penjualan->detail as $detail) {
    //             if ($detail->barang) {
    //                 $penjualan->modal += $detail->barang->harga_beli * $detail->qty;
    //                 $penjualan->barang_terjual += $detail->qty;
    //             }
    //         }

    //         $penjualan->laba = $penjualan->total - $penjualan->modal;

    //         $totalModal += $penjualan->modal;
    //         $totalLaba += $penjualan->laba;
    //         $totalBarangTerjual += $penjualan->barang_terjual;

    //         // hitung piutang (jika status pembayaran belum lunas / masih ada sisa)
    //         if ($penjualan->status_pembayaran !== 'lunas') {
    //             $totalPiutang += $penjualan->sisa; // pastikan ada kolom "sisa" di tabel
    //         }
    //     }

    //     $this->data = [
    //         // 'terlaris'             => $terlaris,
    //         'penjualans'          => $penjualans,
    //         'barang'              => $barang,
    //         'total_penjualan'     => $totalPenjualan,
    //         'total_modal'         => $totalModal,
    //         'total_laba'          => $totalLaba,
    //         'total_barang_terjual'=> $totalBarangTerjual,
    //         'total_piutang'       => $totalPiutang,
    //     ];
    // }

    public function generate()
    {
        $this->validate([
            'tahun' => 'required|digits:4',
        ]);

        // $penjualans = Penjualan::with('detail.barang')
        //     ->whereBetween('tanggal', [$mulai, $selesai])
        //     ->orderBy('tanggal', 'asc')
        //     ->get();

        // Ambil detail penjualan per barang (rekap tahunan)
        $penjualans = DetailPenjualan::with('penjualan.barang')
            ->whereHas('penjualan', fn($q) => $q->whereYear('tanggal', $this->tahun))
            ->selectRaw('barang_id, SUM(qty) as total_terjual, SUM(subtotal) as total_subtotal')
            ->groupBy('barang_id')
            ->get();

        $rekap = [];

        $totalPenjualan = 0;
        $totalModal = 0;
        $totalLaba = 0;
        $totalPiutang = 0;

        foreach ($penjualans as $p) {
            $barang = $p->barang;

            if (!$barang) {
                continue;
            }

            $modal = $barang->harga_beli * $p->total_terjual;
            $laba  = $p->total_subtotal - $modal;

            // Hitung piutang dari semua penjualan barang ini
            $piutang = DetailPenjualan::where('barang_id', $p->barang_id)
                ->whereHas('penjualan', fn($q) => $q
                    ->whereYear('tanggal', $this->tahun)
                    ->where('status_pembayaran', '!=', 'lunas')
                )
                ->sum('subtotal');

            $rekap[] = [
                'nama_barang'   => $barang->nama_barang,
                'terjual'       => $p->total_terjual,
                'harga_beli'    => $barang->harga_beli,
                'harga_jual'    => $barang->harga_jual,
                'subtotal'      => $p->total_subtotal,
                'piutang'       => $piutang,
                'laba'          => $laba,
            ];

            $totalPenjualan += $p->total_subtotal;
            $totalModal     += $modal;
            $totalLaba      += $laba;
            $totalPiutang   += $piutang;
        }

        $this->data = [
            'penjualans'      => $penjualans,
            'rekap'           => $rekap,
            'total_penjualan' => $totalPenjualan,
            'total_modal'     => $totalModal,
            'total_laba'      => $totalLaba,
            'total_piutang'   => $totalPiutang,
        ];
    }



}
