<?php

namespace App\Filament\Resources\BarangBeliResource\Pages;

use App\Filament\Resources\BarangBeliResource;
use App\Models\StokBarang;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateBarangBeli extends CreateRecord
{
    protected static string $resource = BarangBeliResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Barang Beli Berhasil Dibuat';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['id'] = (string) Str::uuid();
        return $data;
    }

    protected function afterCreate(): void
    {
        foreach ($this->record->detailBarangBeli as $detail) {
            StokBarang::create([
                'id'         => (string) \Illuminate\Support\Str::uuid(),
                'barang_id' => $detail->barang_id,
                'barang_beli_id' => $this->record->id,
                'stok_masuk' => $detail->stok,
                'stok_keluar' => 0,
                'stok_sisa' => $detail->stok,
                'harga_satuan' => $detail->harga_satuan,
                'tanggal_masuk'  => now()->toDateString(),
            ]);

            // Update stok total di tabel barang
            $barang = $detail->barang;
            $barang->stok += $detail->stok;
            $barang->save();
        }
    }
}