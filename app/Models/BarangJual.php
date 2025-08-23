<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BarangJual extends Model
{
    use HasFactory, HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'barang_id', 
        'harga_jual'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
            }
        });

        static::created(function ($barangJual) {
        $barang = $barangJual->barang;

        if ($barang) {
            // Update harga_jual ke tabel barangs
            $barang->harga_jual = $barangJual->harga_jual;
            $barang->save();
        }
    });

        static::updated(function ($barangJual) {
            $barang = $barangJual->barang;

            if ($barang) {
                $barang->harga_jual = $barangJual->harga_jual;
                $barang->save();
            }
        });
    }

    
}
