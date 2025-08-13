<?php

namespace App\Observers;

use App\Models\Penjualan;
use App\Models\Barang;

class PenjualanObserver
{
    /**
     * Handle the Penjualan "created" event.
     */

    public function deleting(Penjualan $penjualan): void
    {
        foreach ($penjualan->detail as $detail) {
            $barang = Barang::find($detail->barang_id);
            if ($barang) {
                $barang->stok += $detail->qty;
                $barang->save();
            }
        }
    }

    public function created(Penjualan $penjualan): void
    {
        //
    }

    /**
     * Handle the Penjualan "updated" event.
     */
    public function updated(Penjualan $penjualan): void
    {
        //
    }

    /**
     * Handle the Penjualan "deleted" event.
     */
    public function deleted(Penjualan $penjualan): void
    {
        //
    }

    /**
     * Handle the Penjualan "restored" event.
     */
    public function restored(Penjualan $penjualan): void
    {
        //
    }

    /**
     * Handle the Penjualan "force deleted" event.
     */
    public function forceDeleted(Penjualan $penjualan): void
    {
        //
    }
}