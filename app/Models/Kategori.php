<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_kategori',
        'nama_kategori',
    ];

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $nama = $data['nama_kategori'] ?? '';
        $words = preg_split('/\s+/', trim($nama));
        $code = '';

        if (count($words) === 1) {
            $code = strtoupper(substr($words[0], 0, 3));
        } elseif (count($words) === 2) {
            $code = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 2));
        } else {
            $code = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1) . substr($words[2], 0, 1));
        }

        $data['kode_kategori'] = $code;
        return $data;
    }
}