<?php

namespace App\Filament\Resources;

namespace App\Filament\Resources;

use App\Filament\Resources\KaryawanResource\Pages;
use App\Models\Karyawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Mendefinisikan form untuk membuat dan mengedit karyawan.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_karyawan')
                    ->label('ID Karyawan')
                    ->disabled()  // Membuat field ini tidak bisa diubah
                    ->default(function () {
                        return 'KAR' . str_pad(Karyawan::max('id_karyawan') + 1, 5, '0', STR_PAD_LEFT); // ID otomatis
                    }),
                Forms\Components\TextInput::make('nama_karyawan')
                    ->label('Nama Karyawan')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Masukkan Nama Karyawan'),  // Menambahkan placeholder
                Forms\Components\Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->required(),
                Forms\Components\TextArea::make('alamat')
                    ->label('Alamat')
                    ->required()
                    ->maxLength(500)
                    ->placeholder('Masukkan Alamat'),  // Menambahkan placeholder
                Forms\Components\TextInput::make('nomor_telepon')
                    ->label('Nomor Telepon')
                    ->required()
                    ->maxLength(15)
                    ->placeholder('Masukkan No Telepon'),  // Menambahkan placeholder
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email()
                    ->unique(Karyawan::class, 'email')
                    ->placeholder('Masukkan Email'),  // Menambahkan placeholder
            ]);
    }

    /**
     * Mendefinisikan tabel untuk menampilkan daftar karyawan.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_karyawan')->label('ID Karyawan'),
                Tables\Columns\TextColumn::make('nama_karyawan')->label('Nama Karyawan'),
                Tables\Columns\TextColumn::make('jenis_kelamin')->label('Jenis Kelamin'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('nomor_telepon')->label('Nomor Telepon'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('delete')  // Definisikan bulk action untuk delete
                    ->label('Hapus')  // Label untuk action
                    ->action(function ($records) {
                        // Periksa apakah record valid dan bukan null sebelum dihapus
                        foreach ($records as $record) {
                            if ($record instanceof Karyawan) {
                                $record->delete();  // Hapus record jika valid
                            }
                        }
                    })
                    ->requiresConfirmation(),  // Menambahkan konfirmasi
            ]);
    }

    /**
     * Mendefinisikan relasi yang terkait dengan resource ini.
     */
    public static function getRelations(): array
    {
        return [
            // Tambahkan relasi jika ada
        ];
    }

    /**
     * Mendefinisikan halaman-halaman terkait dengan resource ini.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKaryawans::route('/'),
            'create' => Pages\CreateKaryawan::route('/create'),
            'edit' => Pages\EditKaryawan::route('/{record}/edit'),
        ];
    }
}
