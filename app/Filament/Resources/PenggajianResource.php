<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenggajianResource\Pages;
use App\Filament\Resources\PenggajianResource\RelationManagers;
use App\Models\Penggajian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Karyawan;
use App\Models\Absensi;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\TextInput;



class PenggajianResource extends Resource
{
    protected static ?string $model = Penggajian::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('karyawan_id')
                ->label('Karyawan')
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
                        $set('upah_per_jam', $karyawan->upah_per_jam);
                    }
                })
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
            
            
                Forms\Components\DatePicker::make('periode_mulai')
                ->label('Periode Mulai')
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($set, $get) => PenggajianResource::updatePerhitungan($get, $set)),
    
            Forms\Components\DatePicker::make('periode_selesai')
                ->label('Periode Selesai')
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($set, $get) => PenggajianResource::updatePerhitungan($get, $set)),
    

            Forms\Components\TextInput::make('total_jam')
                ->numeric()
                ->required()
                ->disabled()
                ->dehydrated(),

            Forms\Components\TextInput::make('upah_per_jam')
                ->label('upah per jam')
                ->required()
                ->readonly() 
                ->reactive(), 

            Forms\Components\TextInput::make('total_gaji')
                ->numeric()
                ->required(),

            Forms\Components\DatePicker::make('tanggal_pembayaran')
                ->label('Tanggal Pembayaran')
                ->nullable(),

            Forms\Components\Select::make('status')
                ->options([
                    'belum dibayar' => 'Belum Dibayar',
                    'sudah dibayar' => 'Sudah Dibayar',
                ])
                ->required(),
            ]);
            
    }
    protected static function updatePerhitungan(Get $get, Set $set)
    {
        $karyawanId = $get('karyawan_id');
        $mulai = $get('periode_mulai');
        $selesai = $get('periode_selesai');
        $upah = $get('upah_per_jam') ?? 0;

        if ($karyawanId && $mulai && $selesai) {
            $totalJam = Absensi::where('id_karyawan', $karyawanId)
                ->whereBetween('tanggal', [$mulai, $selesai])
                ->sum('total_jam_hari_ini');

            $set('total_jam', $totalJam);
            $set('total_gaji', $totalJam * $upah);
        } else {
            $set('total_jam', 0);
            $set('total_gaji', 0);
        }
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('karyawan.nama_karyawan')
                    ->label('Nama Karyawan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('periode_mulai')
                    ->label('Periode Mulai')
                    ->date(),

                Tables\Columns\TextColumn::make('periode_selesai')
                    ->label('Periode Selesai')
                    ->date(),

                Tables\Columns\TextColumn::make('total_jam')
                    ->label('Total Jam')
                    ->suffix(' jam'),

                Tables\Columns\TextColumn::make('upah_per_jam')
                    ->label('Upah per Jam')
                    ->formatStateUsing(fn ($state) => rupiah($state)),

                Tables\Columns\TextColumn::make('total_gaji')
                    ->label('Total Gaji')
                    ->formatStateUsing(fn ($state) => rupiah($state)),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => $state === 'dibayar' ? 'success' : 'warning'),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('status')
                ->options([
                    'belum dibayar' => 'Belum Dibayar',
                    'dibayar' => 'Dibayar',
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListPenggajians::route('/'),
            'create' => Pages\CreatePenggajian::route('/create'),
            'edit' => Pages\EditPenggajian::route('/{record}/edit'),
        ];
    }
}
