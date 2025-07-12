<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $fillable = [
        'no_invoice',
        'pelanggan_id',
        'total',
        'tanggal',
        'bayar',
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
    }
}