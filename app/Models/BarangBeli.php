<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class BarangBeli extends Model
{
    use HasFactory, HasUuids;
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'no_invoice',
        'supplier_id',
        'user_id',
        'tanggal_beli',
    ];

    protected static function booted()
    {
        parent::boot();

        // static::creating(function ($model) {
        //     if (empty($model->id)) {
        //         $model->id = (string) \Illuminate\Support\Str::uuid();
        //     }
        // });

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }




    public function detailBarangBeli()
    {
        return $this->hasMany(DetailBarangBeli::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
