<?php

namespace App\Console\Commands;

use App\Models\Barang;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RegenerateQrCode extends Command
{
    protected $signature   = 'qrcode:regenerate';
    protected $description = 'Regenerate semua QR code barang';

    public function handle()
    {
        $barangs = Barang::all();

        $this->info('Mulai regenerate QR code untuk ' . $barangs->count() . ' barang...');

        $bar = $this->output->createProgressBar($barangs->count());
        $bar->start();

        foreach ($barangs as $barang) {

            $qrData = route('master-barang.show', $barang->id);

            $qrFolder = storage_path('app/public/qrcode');
            if (!file_exists($qrFolder)) {
                mkdir($qrFolder, 0755, true);
            }

            $namaQr     = 'barang-' . Str::slug($barang->nama_barang) . '-' . $barang->id . '.svg';
            $qrFileName = 'qrcode/' . $namaQr;
            $qrFilePath = storage_path('app/public/' . $qrFileName);

            QrCode::format('svg')
                  ->size(200)
                  ->errorCorrection('H')
                  ->generate($qrData, $qrFilePath);

            $barang->update(['qr_code' => $qrFileName]);

            $bar->advance();
        }

        $bar->finish();

        $this->newLine();
        $this->info('Selesai! Semua QR code berhasil di-regenerate.');
    }
}