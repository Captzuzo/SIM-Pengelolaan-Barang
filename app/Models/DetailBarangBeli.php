<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DetailBarangBeli extends Model
{
    use HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'barang_beli_id',
        'barang_id',
        'stok',
        'harga_satuan'
    ];

    public function detailBarangBeli()
    {
        return $this->hasMany(DetailBarangBeli::class, 'barang_id');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'barang_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    // Update stok barang otomatis setelah detail pembelian dibuat
    protected static function booted()
    {
        // static::created(function ($detail) {
        //     $barang = $detail->barang;
        //     $barang->stok += $detail->stok;
        //     $barang->save();
        // });
    }
}
