<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class Barang extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'kode_barang',
    //     'kategori_id',
    //     'nama_barang',
    //     'harga_beli',
    //     'harga_jual',
    //     'stok',
    //     'satuan',
    // ];

    // public function kategori()
    // {
    //     return $this->belongsTo(Kategori::class);
    // }

    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'kode_barang',
        'kategori_id',
        'nama_barang',
        'harga_beli',
        'harga_jual',
        'satuan',
        'stok'
    ];

    protected static function boot()
    {
        parent::boot();

        // Generate UUID saat create
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function barangBeli(): BelongsTo
    {
        return $this->belongsTo(BarangBeli::class, 'barang_beli_id');
    }
    public function detailBarangBeli()
    {
        return $this->hasMany(DetailBarangBeli::class, 'barang_id');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'barang_id');
    }

    // public function detailBarangBeli()
    // {
    //     return $this->hasMany(DetailBarangBeli::class, 'barang_id', 'id');
    // }

    public function barangJual()
    {
        return $this->hasOne(BarangJual::class);
    }


    // public function detailBarangBeli()
    // {
    //     return $this->hasMany(DetailBarangBeli::class);
    // }
}
