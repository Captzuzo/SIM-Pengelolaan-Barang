<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MutasiStokModel extends Model
{
    use HasFactory;
    protected $table = 'mutasi_stoks';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'barang_id',
        'tipe',
        'qty',
        'harga_beli',
        'keterangan',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
