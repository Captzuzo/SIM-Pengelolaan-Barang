<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailBarangBeli extends Model
{
    use HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'barang_beli_id',
        'barang_id',
        'stok',
        'harga_satuan',
        'subtotal',
        'tanggal_beli',
    ];

    public function detailBarangBeli()
    {
        return $this->hasMany(DetailBarangBeli::class, 'barang_id');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'barang_id');
    }

    // public function barang()
    // {
    //     return $this->belongsTo(Barang::class, 'barang_id');
    // }

    // public function barangBeli()
    // {
    //     return $this->belongsTo(\App\Models\BarangBeli::class, 'barang_beli_id');
    // }

    public function barangBeli(): BelongsTo
    {
        return $this->belongsTo(BarangBeli::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
    public function stokBarangs()
    {
        return $this->hasMany(StokBarang::class, 'detail_barang_beli_id');
    }


    protected static function booted()
    {
        // Setelah detail pembelian dibuat
        static::created(function ($detail) {
            $barang = $detail->barang;

            if ($barang) {
                // update harga_beli dari harga_satuan
                $barang->harga_beli = $detail->harga_satuan;

                // update stok juga kalau mau
                // $barang->stok = $barang->stok + $detail->stok;

                $barang->save();
            }
        });

        static::updated(function ($detail) {
            $barang = $detail->barang;

            if ($barang) {
                $barang->harga_beli = $detail->harga_satuan;
                $barang->save();
            }
        });

        static::deleting(function ($barangBeli) {
            // Hapus semua stok barangs terkait
            $barangBeli->stokBarangs()->each(function ($stok) {
                $stok->delete();
            });

            // Ambil semua detail pembelian
            $barangBeli->detailBarangBeli->each(function ($detail) {
                $barang = $detail->barang;

                if ($barang) {
                    // Kurangi stok (balikin jumlah stok yang ditambahkan dari pembelian ini)
                    $barang->harga_beli = 0; // reset harga beli
                    $barang->save();
                }

                // Hapus detail pembelian juga biar konsisten
                $detail->delete();
            });
        });

    }

    // Update stok barang otomatis setelah detail pembelian dibuat
    // protected static function booted($request, Request $barangBeli)
    // {
    //     // static::created(function ($detail) {
    //     //     $barang = $detail->barang;
    //     //     $barang->stok += $detail->stok;
    //     //     $barang->save();
    //     // });

    //     $barangBeli = BarangBeli::all();

    //     StokBarang::create([
    //         'barang_id'      => $request->barang_id, // wajib diisi
    //         'barang_beli_id' => $barangBeli->id,
    //         'stok'           => $request->stok,
    //         'harga_beli'     => $request->harga_beli,
    //         'tanggal_beli'   => now(),
    //     ]);
    // }

    // protected static function booted()
    // {
    //     static::created(function ($barangBeli) {
    //         StokBarang::create([
    //             'barang_id'      => $barangBeli->barang_id,
    //             'barang_beli_id' => $barangBeli->id,
    //             'stok'           => $barangBeli->stok,
    //             'harga_beli'     => $barangBeli->harga_beli,
    //             'tanggal_beli'   => now(),
    //         ]);
    //     });
    // }
}
