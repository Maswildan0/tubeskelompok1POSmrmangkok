<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Filament\Resources\PenjualanResource\RelationManagers;
use App\Models\Penjualan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// Komponen Filament
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Filters\SelectFilter;

// Model
use App\Models\Pembeli;
use App\Models\Menu;
use App\Models\Pembayaran;
use App\Models\PenjualanMenu;
use Filament\Forms\Get;


// DB
use Illuminate\Support\Facades\DB;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Penjualan Menu';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Pesanan')
                        ->schema([
                            Forms\Components\Section::make('Faktur')
                                ->icon('heroicon-m-document-duplicate')
                                ->schema([ 
                                    TextInput::make('no_faktur')
                                        ->default(fn () => Penjualan::getKodeFaktur())
                                        ->label('Nomor Faktur')
                                        ->required()
                                        ->readonly(),
                                    DateTimePicker::make('tgl')->default(now()),
                                    Select::make('pembeli_id')
                                        ->label('Pembeli')
                                        ->options(Pembeli::pluck('nama_pembeli', 'id')->toArray())
                                        ->required()
                                        ->placeholder('Pilih Pembeli'),
                                    TextInput::make('tagihan')->default(0)->hidden(),
                                    TextInput::make('status')->default('pesan')->hidden(),
                                ])
                                ->collapsible()
                                ->columns(3),
                        ]),
                    Wizard\Step::make('Pilih Menu')
                        ->schema([
                            Repeater::make('items')
                                ->relationship('penjualanmenu')
                                ->schema([
                                    Select::make('menu_id')
                                        ->label('Menu')
                                        ->options(Menu::pluck('nama', 'id')->toArray())
                                        ->required()
                                        ->reactive()
                                        ->placeholder('Pilih Menu')
                                        ->afterStateUpdated(function ($state, $set) {
                                            $menu = Menu::find($state);
                                            $set('harga_beli', $menu ? $menu->harga : 0);
                                            $set('harga_jual', $menu ? $menu->harga * 1.2 : 0);
                                        })
                                        ->searchable(),
                                    TextInput::make('harga_beli')
                                        ->label('Harga Beli')
                                        ->numeric()
                                        ->readonly()
                                        ->dehydrated()
                                        ->hidden(),
                                    TextInput::make('harga_jual')
                                        ->label('Harga Menu')
                                        ->numeric()
                                        ->readonly()
                                        ->dehydrated(),
                                    TextInput::make('jml')
                                        ->label('Jumlah')
                                        ->default(1)
                                        ->reactive()
                                        ->required(),
                                    DatePicker::make('tgl')
                                        ->default(today())
                                        ->required(),
                                ])
                                ->columns(['md' => 4])
                                ->addable()
                                ->deletable()
                                ->reorderable()
                                ->createItemButtonLabel('Tambah Menu')
                                ->minItems(1)
                                ->required(),

                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('Simpan Sementara')
                                        ->action(function ($get) {
                                            $penjualan = Penjualan::updateOrCreate(
                                                ['no_faktur' => $get('no_faktur')],
                                                [
                                                    'tgl' => $get('tgl'),
                                                    'pembeli_id' => $get('pembeli_id'),
                                                    'status' => 'pesan',
                                                    'tagihan' => 0
                                                ]
                                            );
    
                                            // Simpan data barang
                                            foreach ($get('items') as $item) {
                                                PenjualanMenu::updateOrCreate(
                                                    [
                                                        'penjualan_id' => $penjualan->id,
                                                        'menu_id' => $item['menu_id']
                                                    ],
                                                    [
                                                        'harga_beli' => $item['harga_beli'],
                                                        'harga_jual' => $item['harga_jual'],
                                                        'jml' => $item['jml'],
                                                        'tgl' => $item['tgl'],
                                                    ]
                                                );
    
                                                // Kurangi stok barang di tabel barang
                                                $menu = Menu::find($item['menu_id']);
                                                if ($menu) {
                                                    $menu->decrement('stok', $item['jml']); // Kurangi stok sesuai jumlah barang yang dibeli
                                                }
                                            }
    
                                            // Hitung total tagihan
                                            $totalTagihan = PenjualanMenu::where('penjualan_id', $penjualan->id)
                                                ->sum(DB::raw('harga_jual * jml'));
    
                                            // Update tagihan di tabel penjualan2
                                            $penjualan->update(['tagihan' => $totalTagihan]);
                                                                        })
                                            
                                            ->label('Proses')
                                            ->color('primary'),
                                                                
                                        ])    
           
                            // 
                        ]),
                    Wizard\Step::make('Pembayaran')
                        ->schema([
                            Placeholder::make('Tabel Pembayaran')
                                ->content(fn (Get $get) => view('filament.components.penjualan-table', [
                                    'pembayarans' => Penjualan::where('no_faktur', $get('no_faktur'))->get()
                                ])), 
                        ]),
                ])->columnSpan(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_faktur')->label('No Faktur')->searchable(),
                TextColumn::make('pembeli.nama_pembeli')->label('Nama Pembeli')->sortable()->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'bayar' => 'success',
                        'pesan' => 'warning',
                    }),
                TextColumn::make('tagihan')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.'))
                    ->alignment('end'),
                TextColumn::make('created_at')->label('Tanggal')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options(['pesan' => 'Pemesanan', 'bayar' => 'Pembayaran'])
                    ->searchable(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}
