<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsensiResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;

use App\Models\Karyawan;

class AbsensiResource extends Resource
{
    protected static ?string $model = \App\Models\Absensi::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Absensi';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              Select::make('id_karyawan')
                ->label('ID Karyawan')
                ->relationship('karyawan', 'id_karyawan') //buat nyambung id karyawan
                ->searchable() 
                ->preload() // Memuat opsi lebih awal untuk pengalaman yang lebih cepat
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, callable $set) {
                    if ($state) {
                        // Mencari data karyawan berdasarkan id_karyawan yang dipilih
                        $karyawan = Karyawan::find($state);
                        // Mengisi nama_karyawan setelah id_karyawan dipilih
                        $set('nama_karyawan', $karyawan->nama_karyawan);
                    }
                }),

            TextInput::make('nama_karyawan')
                ->label('Nama Karyawan')
                ->required()
                ->placeholder('Nama karyawan akan terisi otomatis') 
                ->readonly() 
                ->reactive(), 

            DatePicker::make('tanggal')
                ->label('Tanggal')
                ->required()
                ->reactive(),

              TimePicker::make('jam_masuk')
                ->label('Jam Masuk')
                ->required()
                ->withoutSeconds()
                ->format('H:i') // format 24 jam
                ->displayFormat('H:i') // pastikan juga tampilan user 24 jam
                ->reactive()
                ->live()
                ->afterStateUpdated(fn ($state, $get, $set) => static::hitungTotalJam($get, $set)),

            TimePicker::make('jam_keluar')
                ->label('Jam Keluar')
                ->required()
                ->withoutSeconds()
                ->format('H:i')
                ->displayFormat('H:i')
                ->reactive()
                ->live()
                ->afterStateUpdated(fn ($state, $get, $set) => static::hitungTotalJam($get, $set)),

                TextInput::make('total_jam_hari_ini')
                ->label('Total Jam Hari Ini')
                ->readOnly()
                ->dehydrated(true) // ini tu buat nampilin totalnya di form dan masuk ke db juga
                ->reactive(), // buat update nilainya sesuai jam masuk jam keluar
     
                FileUpload::make('bukti_foto')
                    ->directory('absensi_fotos')
                    ->label('Bukti Foto')
                    ->nullable(),
            ]);
    }

    protected static function hitungTotalJam(callable $get, callable $set): void
{
    $jamMasuk = $get('jam_masuk');
    $jamKeluar = $get('jam_keluar');

    if ($jamMasuk && $jamKeluar) {
        $masuk = strtotime($jamMasuk);
        $keluar = strtotime($jamKeluar);

        if ($masuk < $keluar) {
            $selisihMenit = ($keluar - $masuk) / 60;
            $selisihJam = number_format($selisihMenit / 60, 2);
            $set('total_jam_hari_ini', $selisihJam);
        } else {
            $set('total_jam_hari_ini', 0);
        }
    } else {
        $set('total_jam_hari_ini', 0);
    }
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('karyawan.id_karyawan')->label('ID Karyawan')->sortable(),
            TextColumn::make('nama_karyawan')->label('Nama Karyawan')->sortable(),
            TextColumn::make('tanggal')
                ->label('Tanggal')
                ->date('d-m-Y')  // Format tanggal yang diinginkan
                ->sortable(),
            TextColumn::make('jam_masuk')->label('Jam Masuk')->sortable(),
            TextColumn::make('jam_keluar')->label('Jam Keluar')->sortable(),
            TextColumn::make('total_jam_hari_ini')->label('Total Jam Hari Ini')->sortable(),
        ])
        ->filters([
            SelectFilter::make('id_karyawan')
                ->label('Filter Karyawan')
                ->options(function () {
                    return Karyawan::pluck('nama_karyawan', 'id_karyawan')->toArray();
                })
                ->searchable(),
        ])
        ->actions([
            \Filament\Tables\Actions\ViewAction::make(),
            \Filament\Tables\Actions\EditAction::make(),
            \Filament\Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            \Filament\Tables\Actions\BulkActionGroup::make([
                \Filament\Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbsensis::route('/'),
            'create' => Pages\CreateAbsensi::route('/create'),
            'edit' => Pages\EditAbsensi::route('/{record}/edit'),
        ];
    }
}
