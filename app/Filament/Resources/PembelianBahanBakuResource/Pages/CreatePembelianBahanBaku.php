<?php

namespace App\Filament\Resources\PembelianBahanBakuResource\Pages;

use App\Filament\Resources\PembelianBahanBakuResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

use App\Models\DataPembelian;
use App\Models\Pembayaran;
use App\Models\PembelianBahanBaku;
use Illuminate\Support\Facades\DB;

// untuk notifikasi
use Filament\Notifications\Notification;

class CreatePembelianBahanBaku extends CreateRecord
{
    protected static string $resource = PembelianBahanBakuResource::class;

    //penanganan kalau status masih kosong 
    protected function beforeCreate(): void
    {
        $this->data['status'] = $this->data['status'] ?? 'pesan';
    }

    // tambahan untuk simpan
    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('bayar')
                ->label('Bayar')
                ->color('success')
                ->action(fn () => $this->simpanPembayaran())
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Pembayaran')
                ->modalDescription('Apakah Anda yakin ingin menyimpan pembayaran ini?')
                ->modalButton('Ya, Bayar'),
        ];
    }
    // penanganan
    protected function simpanPembayaran()
    {
        // $penjualan = $this->record; // Ambil data penjualan yang sedang dibuat
        $pembelian = $this->record ?? PembelianBahanBaku::latest()->first(); // Ambil pembelian terbaru jika null
        if (!$pembelian) {
        Notification::make()
            ->title('Gagal Menyimpan Pembayaran')
            ->body('Data pembelian tidak ditemukan.')
            ->danger()
            ->send();

        return;
    }
        Pembayaran::create([
            'id_pembelian' => $pembelian->id_pembelian,
            'tgl_bayar'    => now(),
            'jenis_pembayaran' => 'transfer',
            'transaction_time' => now(),
            'gross_amount'       => $pembelian->total_pembelian, // Sesuaikan dengan field di tabel pembayaran
            'virtual_account' => $pembelian->virtual_account,
        ]);

        // Update status penjualan jadi "dibayar"
        $penjualan->update(['status' => 'bayar']);

        // Notifikasi sukses
        Notification::make()
            ->title('Pembayaran Berhasil!')
            ->success()
            ->send();
    }
}