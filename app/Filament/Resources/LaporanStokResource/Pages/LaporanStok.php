<?php

namespace App\Filament\Resources\LaporanStokResource\Pages;

use App\Models\Barang;
use App\Models\DetailPenjualan;
use Filament\Pages\Page;

class LaporanStokPage extends Page
{
    // Page Configuration
    protected static ?string $title = 'Laporan Stok';
    protected static ?string $slug = 'laporan-stok';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?int $navigationSort = 20;
    protected static string $view = 'filament.pages.laporan.laporan-stok';

    // Page State
    public array $barangs = [];
    public float $totalNilaiStok = 0.0;
    public array $barangTerlaris = [];
    public array $barangHampirHabis = [];

    /**
     * Load data saat halaman dimount
     */
    public function mount(): void
    {
        $this->loadLaporanStok();
    }

    /**
     * Ambil dan proses data untuk laporan stok
     */
    protected function loadLaporanStok(): void
    {
        // Data barang lengkap dengan kategori
        $this->barangs = Barang::with('kategori')->get()->toArray();

        // Total nilai stok (stok x harga beli)
        $this->totalNilaiStok = collect($this->barangs)->sum(function ($barang) {
            return $barang['stok'] * $barang['harga_beli'];
        });

        // Barang terlaris berdasarkan qty penjualan
        $this->barangTerlaris = DetailPenjualan::with('barang')
            ->selectRaw('barang_id, SUM(qty) as total_terjual')
            ->groupBy('barang_id')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get()
            ->map(fn($item) => [
                'nama_barang' => $item->barang->nama_barang ?? '-',
                'qty' => $item->total_terjual,
            ])
            ->toArray();

        // Barang yang stoknya hampir habis
        $this->barangHampirHabis = Barang::where('stok', '<=', 5)
            ->orderBy('stok')
            ->limit(5)
            ->get(['nama_barang', 'stok'])
            ->toArray();
    }

    /**
     * Cek akses pengguna berdasarkan role/permission
     */
    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole('Admin') || $user->can('View Laporan Stok'));
    }

    /**
     * Kirim data ke view
     */
    public function getViewData(): array
    {
        return [
            'barangs' => $this->barangs,
            'totalNilaiStok' => $this->totalNilaiStok,
            'barangTerlaris' => $this->barangTerlaris,
            'barangHampirHabis' => $this->barangHampirHabis,
        ];
    }
}
