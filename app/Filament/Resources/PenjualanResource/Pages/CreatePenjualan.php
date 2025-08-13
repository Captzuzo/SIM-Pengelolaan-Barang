<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Services\StokService;

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
        // Pastikan detail sudah dimuat
        $this->record->load('detail');

        foreach ($this->record->detail as $detail) {
            StokService::keluarkanStokFIFO($detail->barang_id, $detail->qty);
        }
    }

    protected function afterSave(): void
    {
        $this->record->load('detail');
        foreach ($this->record->detail as $detail) {
            StokService::keluarkanStokFIFO($detail->barang_id, $detail->qty);
        }
    }
}