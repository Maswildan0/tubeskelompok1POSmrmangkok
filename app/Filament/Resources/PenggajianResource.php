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

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Radio;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;

use App\Models\Karyawan;

// tambahan untuk tombol unduh pdf
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\Action as TableAction; // alias agar jelas
use Barryvdh\DomPDF\Facade\Pdf; // Kalau kamu pakai DomPDF
use Illuminate\Support\Facades\Storage;


class PenggajianResource extends Resource
{
    protected static ?string $model = Penggajian::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id_gaji')
                    ->default(fn () => Penggajian::getIdGaji()) // Ambil default dari method getKodeBarang
                    ->label('ID Gaji')
                    ->required()
                    ->readonly() // Membuat field menjadi read-only
                ,

                Select::make('id_karyawan')
                    ->label('ID_Karyawan')
                    ->options(fn () => Karyawan::pluck('id_karyawan', 'id_karyawan')) // Ambil data pelanggan
                    ->searchable() // Bisa dicari
                    ->preload() // Load semua data untuk performa lebih baik
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $karyawan = Karyawan::where('id_karyawan', $state)->first();
                            $set('nama_karyawan', $karyawan?->nama_karyawan); // pakai optional chaining untuk aman
                        }
                    })
                ,
                                
                TextInput::make('nama_karyawan')
                    ->label('Nama Karyawan')
                    ->required()
                    ->placeholder('Masukkan Nama Karyawan')
                    ->readonly()
                ,

                TextInput::make('jabatan')
                    ->label('Jabatan')
                    ->required()
                    ->placeholder('Masukkan Jabatan')
                ,

                TextInput::make('total_jam_kerja')
                    ->label('Total Jam Kerja')
                    ->numeric()
                    ->live()
                    ->required()
                    ->placeholder('Masukkan Total Jam Kerja')
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('gaji', $state * 100000);
                    })
                , 

                TextInput::make('gaji')
                    ->label('Gaji')
                    ->required()
                    ->placeholder('Masukkan Nominal Gaji')
                    ->readonly()
                , 

                // FileUpload::make('slip_gaji')
                //     ->label('Slip Gaji')
                //     ->directory('documents')
                //     ->columnSpan(2)
                //     ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_gaji')
                    -> label('ID Gaji'),
                TextColumn::make('id_karyawan')
                    -> label('ID Karyawan'),

                TextColumn::make('nama_karyawan'),
                TextColumn::make('jabatan'),
                TextColumn::make('total_jam_kerja'),
                TextColumn::make('gaji'),

                // TextColumn::make('dokumen')
                // ->label('Dokumen')
                // ->url(fn($record) => asset('storage/' . $record->file_path), true)
                // ->formatStateUsing(fn($state) => $state 
                //     ? '<a href="' . asset('storage/' . $state) . '" target="_blank"><i class="fas fa-file-pdf"></i> 📄 </a>' 
                //     : 'Tidak Ada File')
                // ->html(), // Pastikan menggunakan html() agar bisa merender HTML
                // // Buka file saat diklik

                
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('downloadPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function (Penggajian $record) {
                        $pdf = Pdf::loadView('pdf.penggajian', [
                            'penggajian' => $record, // <<< singular
                        ]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'slip-gaji-'.$record->id_gaji.'.pdf'
                        );
                    }),

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
