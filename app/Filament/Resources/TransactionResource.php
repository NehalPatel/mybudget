<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Account;
use App\Models\Bank;
use App\Models\Category;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Transaction')
                    ->columns(3)
                    ->schema([
                        Select::make('transaction_type')
                            ->live()
                            ->options(config('myconfig.transaction_types')),

                        DatePicker::make('transaction_date')
                            ->label('Transaction Date')
                            ->native(false),

                        Select::make('category_id')
                            ->required()
                            ->label('Category')
                            ->native(false)
                            ->options(Category::pluck('name', 'id')->all()),


                        Select::make('account_id')
                            ->required()
                            ->label('To Account')
                            ->native(false)
                            ->options(Account::pluck('name', 'id')->all()),

                        Select::make('from_bank_id')
                            ->required()
                            ->label('From Bank')
                            ->native(false)
                            ->visible(
                                function (Forms\Get $get) : bool{
                                    return $get('transaction_type') == 'transfer';
                                }
                            )
                            ->options(Bank::pluck('name', 'id')->all()),

                        Select::make('to_bank_id')
                            ->required()
                            ->label('To Bank')
                            ->native(false)
                            ->visible(
                                function (Forms\Get $get) : bool{
                                    return $get('transaction_type') == 'transfer';
                                }
                            )
                            ->options(Bank::pluck('name', 'id')->all()),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->columnSpan(3),

                        Select::make('tags')
                            ->preload()
                            ->columnSpan(3)
                            ->relationship('tags', 'name')
                            ->multiple(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
