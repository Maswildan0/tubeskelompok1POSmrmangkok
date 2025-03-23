<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KaryawanResource\Pages;
use App\Filament\Resources\KaryawanResource\RelationManagers;
use App\Models\Karyawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id_karyawan')
                    ->label('ID Karyawan')
                    ->default(fn () => Karyawan::getIdKaryawan()) // Ambil default dari method getIdKaryawan
                    ->required()
                    ->readonly(), // Membuat field menjadi read-only
                TextInput::make('nama_karyawan')
                    ->label('Nama Karyawan')
                    ->required()
                    ->placeholder('Masukkan nama karyawan'),
                Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->required()
                    ->placeholder('Pilih jenis kelamin'),
                TextInput::make('no_telepon')
                    ->label('No Telepon')
                    ->required()
                    ->tel() // Format input telepon
                    ->placeholder('Masukkan no telepon'),
                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email() // Validasi sebagai email
                    ->placeholder('Masukkan email karyawan'),
                Textarea::make('alamat')
                    ->label('Alamat')
                    ->required()
                    ->placeholder('Masukkan alamat lengkap'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_karyawan'),
                TextColumn::make('nama_karyawan'),
                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn ($state) => $state === 'L' ? 'Laki-laki' : 'Perempuan'),
                TextColumn::make('no_telepon'),
                TextColumn::make('email'),
                TextColumn::make('alamat'),
            ])
            ->filters([/* Filter options if any */])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define any relations if required
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKaryawans::route('/'),
            'create' => Pages\CreateKaryawan::route('/create'),
            'edit' => Pages\EditKaryawan::route('/{record}/edit'),
        ];
    }
}
