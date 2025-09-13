<?php

use App\Exports\LaporanHarianExport;
use App\Http\Controllers\BarangBeliController;
use App\Http\Controllers\CetakLaporanBulananController;
use App\Http\Controllers\CetakLaporanHarianController;
use App\Http\Controllers\CetakLaporanLabaController;
use App\Http\Controllers\CetakLaporanStokController;
use App\Http\Controllers\CetakLaporanTahunanController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

// Route::get('/', function () {
//     return view('landingpage');
// });

Route::get('/', function () {
    return redirect('/ht/login');
});
Route::post('/logout', function () {
    Auth::logout();
    session()->flash('logout_success', true);
    return redirect()->route('filament.auth.login');
})->name('logout');

// Route::get('/penjualan/{id}/invoice-penjualan', [InvoiceController::class, 'cetak'])->name('penjualan.invoice-penjualan');
Route::get('/penjualan/{id}/invoice-penjualan', [InvoiceController::class, 'cetak'])->name('penjualan.invoice-penjualan');

Route::get('/barang-beli/{id}/invoice', [BarangBeliController::class, 'cetak'])
    ->name('barangBeli.barang-beli');

Route::get('/laporan-laba/cetak', [CetakLaporanLabaController::class, 'cetak'])->name('laporan-laba.cetak');
Route::get('/laporan-stok/cetak', [CetakLaporanStokController::class, 'cetak'])->name('laporan-stok.cetak');
Route::get('/laporan-harian/cetak', [CetakLaporanHarianController::class, 'cetak'])->name('laporan-harian.cetak');
Route::get('/laporan-bulanan/cetak', [CetakLaporanBulananController::class, 'cetak'])->name('laporan-bulanan.cetak');
Route::get('/laporan-tahunan/cetak/{tahun}', [CetakLaporanTahunanController::class, 'cetak'])
    ->name('laporan-tahunan.cetak');
Route::get('/laporan-harian/excel', function () {
    $tanggalMulai   = request('tanggalMulai');
    $tanggalSelesai = request('tanggalSelesai');
    return Excel::download(new LaporanHarianExport($tanggalMulai, $tanggalSelesai), 'laporan-harian.xlsx');
})->name('laporan-harian.excel');
// Route::get('/laporan-tahunan/cetak', [CetakLaporanTahunanController::class, 'cetak'])->name('laporan-tahunan.cetak');
