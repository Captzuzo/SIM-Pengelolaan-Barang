<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePenjualan extends CreateRecord
{
    protected static string $resource = PenjualanResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Penjualan Berhasil Dibuat';
    }

    protected function afterCreate(): void
    {
        foreach ($this->record->details as $detail) {
            $barang = \App\Models\Barang::find($detail->barang_id);
            if ($barang) {
                $barang->stok -= $detail->qty;
                $barang->save();
            }
        }
    }
}
