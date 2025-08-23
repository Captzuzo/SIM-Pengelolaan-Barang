<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Support\Str;


class StokBarang extends Model
{
    use HasFactory, HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'barang_beli_id',
        'barang_id',
        'stok_masuk',
        'stok_sisa',
        'harga_satuan'
    ];

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::creating(function ($model) {
    //         if (empty($model->{$model->getKeyName()})) {
    //             $model->{$model->getKeyName()} = (string) Str::uuid();
    //         }
    //     });

    //     static::created(function ($stok) {
    //         $barang = Barang::firstOrCreate(
    //             ['barang_beli_id' => $stok->barang_beli_id],
    //             [
    //                 'kode_barang' => strtoupper(Str::random(6)),
    //                 'kategori_id' => null,
    //                 'stok_id' => $stok->id,
    //                 'harga_jual' => $stok->harga_beli, // markup 20%
    //                 'satuan' => 'pcs',
    //                 'stok' => 0,
    //             ]
    //         );

    //         // Update stok dan harga jual
    //         $barang->stok += $stok->jumlah;
    //         $barang->stok_id = $stok->id;
    //         $barang->save();
    //     });
    // }

    // public function barangBeli(): BelongsTo
    // {
    //     return $this->belongsTo(BarangBeli::class, 'barang_beli_id');
    // }

    // public function barang()
    // {
    //     return $this->belongsTo(Barang::class, 'barang_id');
    // }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function barangBeli(): BelongsTo
    {
        return $this->belongsTo(BarangBeli::class, 'barang_beli_id', 'id');
    }
    public function detailBarangBeli()
    {
        return $this->belongsTo(DetailBarangBeli::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->tanggal_masuk)) {
                $model->tanggal_masuk = now()->toDateString();
            }
        });
    }
}