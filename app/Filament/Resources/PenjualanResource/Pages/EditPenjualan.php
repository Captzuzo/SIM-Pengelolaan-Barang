<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\PenjualanResource;
use App\Models\Barang;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenjualan extends EditRecord
{
    protected static string $resource = PenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Penjualan Berhasil Diperbarui';
    }

    /**
     * Kembalikan stok barang saat penjualan dihapus
     */

    protected function afterCreate(): void
    {
        foreach ($this->record->detail as $detail) {
            $barang = \App\Models\Barang::find($detail->barang_id);
            if ($barang) {
                $barang->stok -= $detail->qty;
                $barang->save();
            }
        }
    }
    protected function beforeDelete(): void
    {
        foreach ($this->record->detail as $detail) {
            $barang = Barang::find($detail->barang_id);
            if ($barang) {
                $barang->stok += $detail->qty;
                $barang->save();
            }
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