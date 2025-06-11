<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianBahanBakuResource\Pages;
use App\Filament\Resources\PembelianBahanBakuResource\RelationManagers;
use App\Models\PembelianBahanBaku;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Wizard; //untuk menggunakan wizard
use Filament\Forms\Components\TextInput; //untuk penggunaan text input
use Filament\Forms\Components\DateTimePicker; //untuk penggunaan date time picker
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select; //untuk penggunaan select
use Filament\Forms\Components\Repeater; //untuk penggunaan repeater
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn; //untuk tampilan tabel
use Filament\Forms\Components\Placeholder; //untuk menggunakan text holder
use Filament\Forms\Get; //menggunakan get 
use Filament\Forms\Set; //menggunakan set 
use Filament\Forms\Components\Hidden; //menggunakan hidden field
use Filament\Tables\Filters\SelectFilter; //untuk menambahkan filter

// DB
use Illuminate\Support\Facades\DB;
// untuk dapat menggunakan action
use Filament\Forms\Components\Actions\Action;

use App\Models\Supplier;
use App\Models\BahanBaku;
use App\Models\Pembayaran;
use App\Models\DataPembelian;


class PembelianBahanBakuResource extends Resource
{
    protected static ?string $model = PembelianBahanBaku::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    // merubah nama label menjadi Pembelian
    protected static ?string $navigationLabel = 'Pembelian Bahan Baku';

    // tambahan buat grup masterdata
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
    ->schema([
        Wizard::make([
            Wizard\Step::make('Pesanan')
                ->schema([
                    Forms\Components\Section::make('Pembelian Bahan Baku')
                        ->icon('heroicon-m-document-duplicate')
                        ->schema([
                            TextInput::make('id_pembelian')
                                ->default(fn () => PembelianBahanBaku::getIdPembelian())
                                ->label('ID Pembelian')
                                ->required()
                                ->readonly(),

                            DateTimePicker::make('tgl')->default(now()),

                            Select::make('kode_supplier')
                                ->label('Supplier')
                                ->options(Supplier::pluck('nama_supplier', 'id')->toArray())
                                ->required()
                                ->placeholder('Pilih Supplier'),

                            TextInput::make('tagihan')
                                ->default(0)
                                ->hidden(),

                            TextInput::make('status')
                                ->default('pesan')
                                ->hidden(),
                        ])
                        ->collapsible()
                        ->columns(3),
                ]),

            Wizard\Step::make('Pilih Bahan Baku')
                ->schema([
                    Repeater::make('items')
                        ->relationship('dataPembelian')
                        ->schema([
                            Select::make('kode_bahanbaku')
                                ->label('Bahan Baku')
                                ->options(BahanBaku::pluck('nama_bahanbaku', 'id')->toArray())
                                ->required()
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->reactive()
                                ->placeholder('Pilih Bahan Baku')
                                ->afterStateUpdated(function ($state, $set) {
                                    $bhnbaku = BahanBaku::find($state);
                                    $set('harga_beli', $bhnbaku ? $bhnbaku->harga_beli : 0);
                                })
                                ->searchable(),

                            TextInput::make('harga_beli')
                                ->label('Harga Beli')
                                ->default(fn ($get) => BahanBaku::find($get('kode_bahanbaku') ? BahanBaku::find($get('kode_bahanbaku'))?->harga_beli ?? 0 : 0))
                                ->numeric()
                                ->required()
                                ->readonly()
                                ->dehydrated(),

                            TextInput::make('jml')
                                ->label('Jumlah')
                                ->default(1)
                                ->reactive()
                                ->live()
                                ->required()
                                ->afterStateUpdated(function ($state, $set, $get) {
                                    $totalTagihan = collect($get('data_pembelian'))
                                        ->sum(fn ($item) => ($item['harga_beli'] ?? 0) * ($item['jml'] ?? 0));
                                    $set('tagihan', $totalTagihan);
                                }),


                            DatePicker::make('tgl')
                                ->default(today())
                                ->required(),
                        ])
                        ->columns(['md' => 4])
                        ->addable()
                        ->deletable()
                        ->reorderable()
                        ->minItems(1)
                        ->required(),

                    Placeholder::make('total_pembelian')
                        ->label('Total Pembelian')
                        ->content(function (Get $get) {
                            $items = $get('items') ?? [];
                            $total = collect($items)->sum(fn ($item) => ($item['harga_beli'] ?? 0) * ($item['jml'] ?? 0));
                            return 'Rp ' . number_format($total, 0, ',', '.');
                        }),


                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('Simpan Sementara')
                            ->action(function ($get) {
                                $pembelian = PembelianBahanBaku::updateOrCreate(
                                    ['id_pembelian' => $get('id_pembelian')],
                                    [
                                        'tgl' => $get('tgl'),
                                        'kode_supplier' => $get('kode_supplier'),
                                        'status' => 'pesan',
                                        'tagihan' => 0,
                                    ]
                                );

                                foreach ($get('items') as $item) {
                                    DataPembelian::updateOrCreate(
                                        [
                                            'id_pembelian' => $pembelian->id,
                                            'kode_bahanbaku' => $item['kode_bahanbaku'],
                                        ],
                                        [
                                            'harga_beli' => $item['harga_beli'],
                                            'jml' => $item['jml'],
                                            'tgl' => $item['tgl'],
                                            'total_pembelian' => $item['harga_beli'] * $item['jml'],
                                        ]
                                    );

                                    // Menambah stok barang di tabel bahan baku
                                    $bhnbaku = BahanBaku::find($item['kode_bahanbaku']);
                                    if ($barang) {
                                    $barang->increment('stok', $item['jml']); // menambah stok sesuai jumlah barang yang dibeli
                                    }
                                }

                                // Hitung total tagihan
                                $totalTagihan = DataPembelian::where('id_pembelian', $pembelian->id)
                                    ->sum(DB::raw('harga_beli * jml'));

                                // Update tagihan di tabel penjualan2
                                $pembelian->update(['tagihan' => $totalTagihan]);
                                })
                                ->label('Proses')
                                ->color('primary'),
                            ]),
                        ]),
                                        
            Wizard\Step::make('Proses Pembayaran')
                ->schema([
                    Forms\Components\Grid::make(6)
                        ->schema([
                            Placeholder::make('total_pembelian')
                                ->label('Total Pembelian')
                                ->content(function (Get $get) {
                                $items = $get('items') ?? [];
                                $total = collect($items)->sum(fn ($item) => ($item['harga_beli'] ?? 0) * ($item['jml'] ?? 0));
                                return 'Rp ' . number_format($total, 0, ',', '.');
                            }),
                    ]),
                ]),

            Wizard\Step::make('Pembayaran')
                ->schema([
                    Forms\Components\Grid::make(3)
                        ->schema([
                            Placeholder::make('Tabel Pembayaran')
                                ->content(fn (Get $get) => view('filament.components.pembelianbahanbaku-table', [
                                    'pembayarans' => PembelianBahanBaku::where('id_pembelian', $get('id_pembelian'))->get(),
                                ])),
                        ]),
                ])
                ->columnSpan(4),
        ]),
        
    ]);
}
                
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_pembelian')->label('ID Pembelian')->searchable(),
                TextColumn::make('supplier.nama_supplier') // Relasi ke nama supplier
                    ->label('Nama Supplier')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('bahanbaku.nama_bahanbaku') // Relasi ke nama bahan baku
                    ->label('Nama Bahan Baku')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'bayar' => 'success',
                        'pesan' => 'warning',
                    }),
                TextColumn::make('tagihan')
                    ->sortable()
                    ->alignment('end') // Rata kanan
                ,
                TextColumn::make('created_at')->label('Tanggal')->dateTime(),
                
                TextColumn::make('total_pembelian')->money('Rp.')->label('Total Pembelian')
                    ->formatStateUsing(fn (string|int|null $state): string => rupiah($state))
                    ,
                ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pesan' => 'Pemesanan',
                        'bayar' => 'Pembayaran',
                    ])
                    ->searchable()
                    ->preload(), // Menampilkan semua opsi saat filter diklik
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
            'index' => Pages\ListPembelianBahanBakus::route('/'),
            'create' => Pages\CreatePembelianBahanBaku::route('/create'),
            'edit' => Pages\EditPembelianBahanBaku::route('/{record}/edit'),
        ];
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $totalTagihan = 0;

        foreach ($data['items'] as $detail) {
            $totalTagihan += $detail['harga_beli'] * $detail['jml'];
        }

        $data['total_pembelian'] = $totalTagihan;

        return $data;
    }

     public static function mutateFormDataBeforeSave(array $data): array
    {
        $totalTagihan = 0;

        foreach ($data['items'] as $detail) {
            $totalTagihan += $detail['harga_beli'] * $detail['jml'];
        }

        $data['total_pembelian'] = $totalTagihan;

        return $data;
    }
}
