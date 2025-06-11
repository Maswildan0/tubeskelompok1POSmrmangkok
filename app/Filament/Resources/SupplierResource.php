<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Pages\EditRecord;


// untuk form dan table

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    // merubah nama label menjadi Supplier
    protected static ?string $navigationLabel = 'Supplier';

    // tambahan buat grup masterdata
    protected static ?string $navigationGroup = 'Masterdata';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_supplier')
                    ->default(fn () => Supplier::getKodeSupplier()) // Ambil default dari method getKodeBarang
                    ->label('Kode Supplier')
                    ->required()
                    ->readonly(), // Membuat field menjadi read-only

                TextInput::make('nama_supplier')
                    ->label('Nama Supplier')
                    ->required(),

                TextInput::make('alamat')
                    ->label('Alamat Supplier')
                    ->required(),

                TextInput::make('telepon')
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
                TextColumn::make('kode_supplier')
                ->searchable(),
                // agar bisa di search
                
                TextColumn::make('nama_supplier')
                ->label('Nama Supplier')
                ->sortable()
                ->searchable(),

                TextColumn::make('alamat')
                ->label('Alamat Supplier')
                ->sortable()
                ->searchable(),

                TextColumn::make('telepon')
                ->label('Nomor Telepon')
                ->sortable()
                ->searchable(),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
