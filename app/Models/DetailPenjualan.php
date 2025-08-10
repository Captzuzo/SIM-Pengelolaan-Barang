<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;


class DetailPenjualan extends Model
{
    use HasUuids;

    protected $keyType = 'string'; // UUID = string
    public $incrementing = false;

    protected $fillable = [
        'penjualan_id',
        'barang_id',
        'qty',
        'harga_satuan',
        'subtotal',
    ];

    protected static function booted(): void
    {
        static::created(function ($detail) {
            $barang = $detail->barang;
            $barang->stok -= $detail->qty;
            $barang->save();
        });

        static::updated(function ($detail) {
            $originalQty = $detail->getOriginal('qty');
            $diff = $detail->qty - $originalQty;

            $barang = $detail->barang;
            $barang->stok -= $diff;
            $barang->save();
        });

        static::deleted(function ($detail) {
            $barang = $detail->barang;
            $barang->stok += $detail->qty;
            $barang->save();
        });
    }

    // public function barang()
    // {
    //     return $this->belongsTo(Barang::class);
    // }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }
}
