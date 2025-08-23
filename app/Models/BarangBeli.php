<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class BarangBeli extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'no_invoice',
        'supplier_id',
        'user_id',
        'tanggal_beli',
    ];

    protected static function booted()
    {
        parent::boot();

        // static::creating(function ($model) {
        //     if (empty($model->id)) {
        //         $model->id = (string) \Illuminate\Support\Str::uuid();
        //     }
        // });
        // static::created(function ($detail) {
        //     $barang = $detail->barang;
        //     $barang->stok += $detail->stok;
        //     $barang->save();
        // });

        // static::created(function ($detailBarangBeli) {
        //     StokBarang::create([
        //         'barang_id'      => $detailBarangBeli->barang_id,
        //         'barang_beli_id' => $detailBarangBeli->barang_beli_id,
        //         'stok'           => $detailBarangBeli->stok,
        //         'harga_beli'     => $detailBarangBeli->harga_satuan, // kalau ini harga beli
        //         'tanggal_beli'   => now(),
        //     ]);
        // });


        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
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




    public function detailBarangBeli()
    {
        return $this->hasMany(DetailBarangBeli::class, 'barang_beli_id');
    }

    // public function detailBarangBeli()
    // {
    //     return $this->hasMany(\App\Models\DetailBarangBeli::class, 'barang_beli_id');
    // }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function stokBarang()
    {
        return $this->hasOne(StokBarang::class);
    }

    public function stokBarangs()
    {
        return $this->hasMany(StokBarang::class, 'barang_beli_id', 'id');
    }
}