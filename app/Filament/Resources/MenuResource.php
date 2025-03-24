<?php

namespace App\Filament\Resources;

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

use App\Filament\Resources\MenuResource\Pages;
use App\Filament\Resources\MenuResource\RelationManagers;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;



class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id_menu')
                    ->default(fn () => Menu::getIdMenu()) // Ambil default dari method getKodeBarang
                    ->label('ID Menu')
                    ->required()
                    ->readonly() // Membuat field menjadi read-only
                ,

                Select::make('id_kategori')
                ->label('Pilih Kategori')
                ->options(fn () => \App\Models\Kategori::pluck('nama_Kategori','id_kategori')) // Ambil data pelanggan
                ->searchable() // Bisa dicari
                ->preload() // Load semua data untuk performa lebih baik
                ->required()

                ,

                TextInput::make('nama')
                ->label('Nama Menu')
                ->required()
                ->placeholder('Masukkan Nama Menu')
                
                ,

                TextInput::make('harga')
                    ->required()
                    ->minValue(0) // Nilai minimal 0 (opsional jika tidak ingin ada harga negatif)
                    ->reactive() // Menjadikan input reaktif terhadap perubahan
                    ->extraAttributes(['id' => 'harga']) // Tambahkan ID untuk pengikatan JavaScript
                    ->placeholder('Masukkan Harga') // Placeholder untuk membantu pengguna
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set) => 
                    $set('harga ', number_format((int) str_replace('.', '', $state), 0, ',', '.'))
                    )

                ,

                FileUpload::make('gambar')
                ->label('Gambar')
                ->image()
                ->directory('images')
                ->required()
                ,

                Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->maxLength(500)
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_menu')
                    ->searchable()
                    -> label('ID Menu'),

                TextColumn::make('kategori.nama_Kategori')
                    ->searchable()
                    ->label('ID Kategori'),
                
                TextColumn::make('nama')
                    ->label('Nama'),
                
                TextColumn::make('harga')
                    ->label('Harga Barang')
                    ->formatStateUsing(fn (string|int|null $state): string => rupiah($state))
                    //->formatStateUsing(fn (string|int|null $state): string => rupiah($state))
                    ->extraAttributes(['class' => 'text-right']) // Tambahkan kelas CSS untuk rata kanan
                    ->sortable(),

                ImageColumn::make('gambar')
                ->label('Gambar')
                ->size(100), 

                TextColumn::make('deskripsi')
                ->label('Deskripsi')
                ->sortable(),

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
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
