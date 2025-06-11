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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// untuk form dan table
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;

// untuk model ke user
use App\Models\User;

class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;

    protected static ?string $navigationIcon = 'heroicon-o-face-smile';
    // merubah nama label menjadi Pembeli
    protected static ?string $navigationLabel = 'Karyawan';

    // tambahan buat grup masterdata
    protected static ?string $navigationGroup = 'Masterdata';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //direlasikan ke tabel user
                Select::make('user_id')
                    ->label('User Id')
                    ->relationship('user', 'email')
                    ->searchable() // Menambahkan fitur pencarian
                    ->preload() // Memuat opsi lebih awal untuk pengalaman yang lebih cepat
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $user = User::find($state);
                            $set('nama_karyawan', $user->name);
                        }
                    })
                , 

                TextInput::make('id_karyawan')
                    ->default(fn () => Karyawan::getKodeKaryawan()) // Ambil default dari method getKodePembeli
                    ->label('id karyawan')
                    ->required()
                    ->readonly() // Membuat field menjadi read-only
                ,
                TextInput::make('nama_karyawan')
                    ->required()
                    ->placeholder('Masukkan nama Karyawan') // Placeholder untuk membantu pengguna
                    // ->live()
                    ->readonly() // Membuat field tidak bisa diketik manual
                ,
                Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->required(),

                TextInput::make('alamat')
                    ->required()
                    ->placeholder('Masukkan alamat pembeli') // Placeholder untuk membantu pengguna
                ,
                TextInput::make('nomor_telepon')
                    ->required()
                    ->placeholder('Masukkan nomor telepon') // Placeholder untuk membantu pengguna
                    ->numeric() // Validasi agar hanya angka yang diizinkan
                    ->prefix('+62') // Contoh: Menambahkan prefix jika diperlukan
                    ->extraAttributes(['pattern' => '^[0-9]+$', 'title' => 'Masukkan angka yang diawali dengan 0']) // Validasi dengan pattern regex
                ,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_karyawan')->label('ID Karyawan'),
                TextColumn::make('nama_karyawan')->label('Nama Karyawan'),
                TextColumn::make('jenis_kelamin')->label('Jenis Kelamin'),
                TextColumn::make('alamat')->label('Alamat'),
                TextColumn::make('nomor_telepon')->label('Nomor Telepon'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
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
