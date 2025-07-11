<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $fillable = [
        'nama',
        'no_hp',
        'provinces',
        'regencies',
        'districts',
        'villages',
        'alamat_lengkap',
    ];

    public function province()
    {
        return $this->belongsTo(\App\Models\Province::class, 'provinces');
    }

    public function regency()
    {
        return $this->belongsTo(\App\Models\Regency::class, 'regencies');
    }

    public function district()
    {
        return $this->belongsTo(\App\Models\District::class, 'districts');
    }

    public function village()
    {
        return $this->belongsTo(\App\Models\Village::class, 'villages');
    }

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class);
    }
    public function details()
    {
        return $this->hasMany(DetailPenjualan::class, 'pelanggan_id');
    }
}