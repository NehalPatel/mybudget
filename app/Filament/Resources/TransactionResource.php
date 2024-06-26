<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Transaction')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('transaction_date')
                            ->label('Transaction Date')
                            ->native(false),

                        Select::make('transaction_type')
                            ->live()
                            ->options(config('myconfig.transaction_types')),

                        Select::make('category_id')
                            ->required()
                            ->label('Category')
                            ->native(false)
                            ->options(function(Get $get){
                                $transactionType = $get('transaction_type');

                                return Category::where('type', $transactionType)
                                    ->pluck('name', 'id')
                                    ->all();
                            }),

                        Select::make('account_id')
                            ->required()
                            ->label('Account')
                            ->native(false)
                            ->options(Account::pluck('name', 'id')->all()),

                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->prefix('₹'),

                        Forms\Components\Textarea::make('description')
                            ->label('Description'),

                        Select::make('tags')
                            ->preload()
                            ->relationship('tags', 'name')
                            ->multiple(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_type')
                    ->label('Type')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('category.name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('account.name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('description')
                    ->sortable()
                    ->limit(25)
                    ->searchable()
                    ->tooltip(function ($record) {
                        return $record->description;
                    }),

                TextColumn::make('amount')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
