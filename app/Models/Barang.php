<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'kategori_id',
        'nama_barang',
        'harga_beli',
        'harga_jual',
        'stok',
        'satuan',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}