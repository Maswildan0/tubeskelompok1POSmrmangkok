<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BahanBakuResource\Pages;
use App\Filament\Resources\BahanBakuResource\RelationManagers;
use App\Models\BahanBaku;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select; //untuk penggunaan select
use Filament\Forms\Components\Repeater; //untuk penggunaan repeater
use Filament\Tables\Columns\TextColumn; //untuk tampilan tabel
use Filament\Forms\Components\Placeholder; //untuk menggunakan text holder
use Filament\Forms\Get; //menggunakan get 
use Filament\Forms\Set; //menggunakan set 
use Filament\Forms\Components\Hidden; //menggunakan hidden field
use Filament\Tables\Filters\SelectFilter; //untuk menambahkan filter

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\TextInput;
// use Filament\Forms\Components\InputMask;
use Filament\Forms\Components\FileUpload; //untuk tipe file
use Filament\Tables\Columns\ImageColumn;

// model
use App\Models\Supplier;

// DB
use Illuminate\Support\Facades\DB;
// untuk dapat menggunakan action
use Filament\Forms\Components\Actions\Action;

class BahanBakuResource extends Resource
{
    protected static ?string $model = BahanBaku::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Masterdata';

    public static function rupiah($amount): string
    {
        if ($amount === null || $amount === '') {
            return '-';
        }
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_bahanbaku')
                    ->default(fn () => BahanBaku::getKodeBhnBaku()) // Ambil default dari method getKodeBarang
                    ->label('Kode Bahan Baku')
                    ->required()
                    ->readonly() // Membuat field menjadi read-only
                ,
                TextInput::make('nama_bahanbaku')
                    ->label('Nama Bahan Baku')
                    ->required(),
                
                Select::make('kode_supplier')
                    ->label('Kode Supplier')
                    ->options(Supplier::pluck('kode_supplier', 'id')->toArray())
                    // Mengambil data dari tabel
                    ->required()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems() //agar komponen item tidak berulang
                    ->reactive() // Membuat field reactive
                    ->placeholder('Pilih Kode Supplier') // Placeholder default
                    ->afterStateUpdated(function ($state, callable $set) {
                    $supplier = \App\Models\Supplier::where('id', $state)->first();
                    $set('nama_supplier', $supplier?->nama_supplier ?? '');
                    })
                    ->searchable()
                    ,

                TextInput::make('nama_supplier')
                    ->label('Nama Supplier')
                    ->default(fn ($get) => Supplier::find($get('kode_supplier'))?->nama_supplier ?? '')
                    ->placeholder('Pilih Nama Supplier') // Placeholder default
                    ->required()
                ,               
                FileUpload::make('foto')
                    ->directory('foto')
                    ->required()
                ,
                TextInput::make('stok')
                    ->required()
                    ->placeholder('Masukkan stok bahan baku') // Placeholder untuk membantu pengguna
                    ->minValue(0)
                ,
                Select::make('satuan')
                ->label('Satuan')
                ->options([
                    'kilogram' => 'Kg',
                    'pcs' => 'Pcs',
                    'liter' => 'Liter',
                   ])
                    ->required(),

                TextInput::make('harga_beli')
                    ->required()
                    ->numeric()
                    ->minValue(0) // Nilai minimal 0 (opsional jika tidak ingin ada harga negatif)
                    ->reactive() // Menjadikan input reaktif terhadap perubahan
                    ->extraAttributes(['kode_bahanbaku' => 'id']) // Tambahkan ID untuk pengikatan JavaScript
                    ->placeholder('Masukkan Harga Bahan Baku') // Placeholder untuk membantu pengguna
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set) => 
                        $set('harga_beli', number_format((int) str_replace('.', '', $state), 0, ',', '.'))
                      )
                ,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_bahanbaku')
                    ->searchable(),
                    // agar bisa di search

                TextColumn::make('nama_bahanbaku')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('kode_supplier')
                    ->searchable(),
                    // agar bisa di search

                TextColumn::make('nama_supplier')
                    ->searchable(),
                    // agar bisa di search

                ImageColumn::make('foto'),
                TextColumn::make('stok'),

                TextColumn::make('satuan')
                ->label('Satuan')
                ->sortable()
                ->searchable(),

                TextColumn::make('harga_beli')
                ->label('Harga Bahan Baku')
                ->sortable()
                ->formatStateUsing(fn (string|int|null $state): string => rupiah($state))
                

            ])
            ->filters([
                //
            ])
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBahanBakus::route('/'),
            'create' => Pages\CreateBahanBaku::route('/create'),
            'edit' => Pages\EditBahanBaku::route('/{record}/edit'),
        ];
    }
}
