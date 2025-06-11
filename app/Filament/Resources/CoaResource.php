<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
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

use App\Filament\Resources\CoaResource\Pages;
use App\Filament\Resources\CoaResource\RelationManagers;
use App\Models\Coa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoaResource extends Resource
{
    protected static ?string $model = Coa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Masterdata';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Select::make('kepala_akun')
                ->label('Kepala Akun')
                ->options([
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                ])
                ->required(),

            Select::make('kelompok_akun')
                ->label('Kelompok Akun')
                ->options([
                    'aset' => 'Aset',
                    'utang' => 'Utang',
                    'modal' => 'Modal',
                    'pendapatan' => 'Pendapatan',
                    'beban' => 'Beban',
                ])
                ->required(),

            TextInput::make('kode_akun')
                ->label('Kode Akun')
                ->required(),

            TextInput::make('nama_akun')
                ->label('Nama Akun')
                ->required(),
            
            Select::make('posisi_akun')
                ->label('Posisi Akun')
                ->options([
                    'debit' => 'Debit',
                    'kredit' => 'Kredit',
                ])
        ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('kepala_akun')
                ->label('Kepala Akun')
                ->sortable()
                ->searchable(),

            TextColumn::make('kelompok_akun')
                ->label('Kelompok Akun')
                ->sortable()
                ->searchable(),

            TextColumn::make('kode_akun')
                ->label('Kode Akun')
                ->sortable()
                ->searchable(),

            TextColumn::make('nama_akun')
                ->label('Nama Akun')
                ->sortable()
                ->searchable(),
            
            TextColumn::make('posisi_akun')
                ->label('Posisi Akun')
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
            'index' => Pages\ListCoas::route('/'),
            'create' => Pages\CreateCoa::route('/create'),
            'edit' => Pages\EditCoa::route('/{record}/edit'),
        ];
    }
}
