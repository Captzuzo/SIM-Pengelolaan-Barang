<?php

namespace App\Filament\Pages\Laporan;;

use App\Filament\Resources\LaporanHarianResource;
use App\Models\Barang;
// use Filament\Pages\Page;
// use Filament\Resources\Pages\Page;

use App\Models\Penjualan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class LaporanHarianPage extends Page
{
    // protected static string $resource = Lapo::class;
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $title = 'Laporan Harian / Bulanan';
    protected static ?string $slug = 'laporan-harian';
    protected static ?int $navigationSort = 21;

    protected static string $view = 'filament.pages.laporan.laporan-harian';
    // protected static string $view = 'filament.pages.laporan.laporan-stok';

    public ?string $tanggal = null;

    public ?string $tanggalMulai = null;
    public ?string $tanggalSelesai = null;
    // public array $data = [
    //     'total_penjualan' => 0,
    //     'total_modal' => 0,
    //     'total_laba' => 0,
    //     'penjualans' => [],
    // ];
    public $penjualans;
    public $perPage = 10;

    public array $data = [];

    // public function mount(): void
    // {
    //     $this->form->fill();
    // }

    public function mount(): void
    {
        // if (! static::canAccess()) {
        //     abort(403, 'Akses ditolak: Anda tidak memiliki izin untuk melihat Laporan Harian.');
        // }
        // $this->penjualans = Penjualan::with('detail.barang')->paginate(10)->toArray();

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

        if ($user->hasPermissionTo('View Laporan Harian')) {
            return true;
        }

        return false;
    }



    // protected function getFormSchema(): array
    // {
    //     return [
    //         Forms\Components\DatePicker::make('tanggal')
    //             ->label('Pilih Tanggal')
    //             ->required(),
    //     ];
    // }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('tanggalMulai')
                ->label('Dari Tanggal')
                ->required(),
            DatePicker::make('tanggalSelesai')
                ->label('Sampai Tanggal')
                ->required(),
        ];
    }

    public function generate()
    {
        $this->validateOnly('tanggal');

        $penjualans = Penjualan::with('detail.barang')
            ->whereBetween('tanggal', [$this->tanggalMulai, $this->tanggalSelesai])
            ->orderBy('tanggal', 'asc')
            // ->paginate($this->perPage);
            ->get();

        $barang = Barang::all();

        // $penjualans = Penjualan::with('detail.barang')
        //     ->whereDate('tanggal', $this->tanggal)
        //     ->get();


        $totalPenjualan = $penjualans->sum('total');
        $totalModal = 0;
        $totalModal = 0;
        $totalLaba = 0;
        $totalPiutang = 0;
        $totalQty = 0;
        $totalBarangBeli = 0;
        $totalBarangJual = 0;


        foreach ($penjualans as $penjualan) {
            $penjualan->modal = 0; // modal per transaksi

            foreach ($penjualan->detail as $detail) {
                if ($detail->barang) {
                    $penjualan->modal += $detail->barang->harga_beli * $detail->qty;

                    // Hitung total qty dan harga di sini
                    $totalQty        += $detail->qty;
                    $totalBarangBeli += $detail->barang->harga_beli;
                    $totalBarangJual += $detail->barang->harga_jual;
                }
            }

            $penjualan->laba = $penjualan->total - $penjualan->modal;

            $totalModal   += $penjualan->modal;
            $totalLaba    += $penjualan->laba;
            $totalPiutang += $penjualan->sisa;
        }

        // simpan paginator di property
        // $this->penjualans = $penjualans;
        // return view('filament.pages.laporan.laporan-harian', [
        //     'penjualans' => $penjualans,
        // ]);

        $this->data = [
            'penjualans' => $penjualans,
            'barang' => $barang,
            'total_penjualan' => $totalPenjualan,
            'total_modal' => $totalModal,
            'total_laba' => $totalLaba,
            'totalPiutang' => $totalPiutang,
            'totalQty' => $totalQty,
            'totalBarangBeli' => $totalBarangBeli,
            'totalBarangJual' => $totalBarangJual,
        ];
    }
}
