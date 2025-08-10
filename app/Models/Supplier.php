<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'no_hp',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'alamat_lengkap',
    ];

    public function barangBeli()
    {
        return $this->hasMany(BarangBeli::class);
    }

    public function detailBarangBeli()
    {
        return $this->hasMany(DetailBarangBeli::class, 'supplier_id');
    }
}
