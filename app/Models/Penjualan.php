<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $fillable = [
        'no_invoice',
        'pelanggan_id',
        'user_id',
        'total',
        'tanggal',
        'bayar',
        'kembalian',
        'sisa',
        'status_pembayaran',
    ];

    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'detail_penjualans');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(DetailPenjualan::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->sisa = $model->total - $model->bayar;
        });

        static::saving(function ($penjualan) {
            // Hitung sisa otomatis
            $penjualan->sisa = $penjualan->total - $penjualan->bayar;

            // Tentukan status pembayaran otomatis
            if ($penjualan->bayar >= $penjualan->total) {
                $penjualan->status_pembayaran = 'lunas';
            } else {
                $penjualan->status_pembayaran = 'belum lunas';
            }
        });
    }
}
