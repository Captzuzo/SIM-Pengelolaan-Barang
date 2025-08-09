<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Barang;
use App\Models\Pelanggan;

class Penjualan extends Model
{
    protected $keyType = 'string'; // UUID = string
    public $incrementing = false;

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
        return $this->belongsToMany(Barang::class, 'detail_penjualans')
            ->withPivot('qty', 'harga_satuan', 'subtotal')
            ->withTimestamps();
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }

            $total = (int) $model->total;
            $bayar = (int) $model->bayar;

            $model->sisa = max(0, $total - $bayar);
            $model->kembalian = max(0, $bayar - $total);
            $model->status_pembayaran = $bayar >= $total ? 'lunas' : 'belum bayar';
        });

        static::saving(function ($model) {
            $total = (int) $model->total;
            $bayar = (int) $model->bayar;

            $model->sisa = max(0, $total - $bayar);
            $model->kembalian = max(0, $bayar - $total);
            $model->status_pembayaran = $bayar >= $total ? 'lunas' : 'belum bayar';
        });
    }
}
