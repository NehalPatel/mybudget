<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Account;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
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
                            ->searchable()
                            ->label('Category')
                            ->native(false)
                            ->options(function (Get $get) {
                                $transactionType = $get('transaction_type');

                                return Category::where('type', $transactionType)
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->all();
                            }),

                        Select::make('account_id')
                            ->searchable()
                            ->required()
                            ->label('Account')
                            ->native(false)
                            ->options(Account::pluck('name', 'id')->all()),

                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->prefix('₹'),

                        Select::make('tags')
                            ->searchable()
                            ->preload()
                            ->relationship('tags', 'name')
                            ->options(function () {
                                return Tag::orderBy('name', 'asc')->pluck('name', 'id');
                            })
                            ->multiple(),

                        Forms\Components\Textarea::make('description')
                            ->columnSpan(2)
                            ->label('Description'),


                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_date')
                    ->dateTime('F j, Y g:i A'),

                TextColumn::make('transaction_type')
                    ->label('Type')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('category.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),

                TextColumn::make('account.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),

                TextColumn::make('description')
                    ->sortable()
                    ->limit(25)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
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
            ->recordClasses(fn(Transaction $record) => match ($record->transaction_type) {
                'expense' => 'border-s-2 border-orange-600 dark:border-orange-300',
                'income' => 'border-s-2 border-green-600 dark:border-green-300',
                default => null,
            })
            ->filters([
                Tables\Filters\SelectFilter::make('transaction_type')
                    ->options(config('myconfig.transaction_types')),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),

                Tables\Filters\SelectFilter::make('account_id')
                    ->label('Account')
                    ->relationship('account', 'name'),

                Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('from')
                            ->label('From Date')
                            ->placeholder('Select start date'),
                        DatePicker::make('to')
                            ->label('To Date')
                            ->placeholder('Select end date'),
                    ])
                    ->query(function ($query, array $data) {
                        if (isset($data['from'])) {
                            $query->whereDate('transaction_date', '>=', $data['from']);
                        }

                        if (isset($data['to'])) {
                            $query->whereDate('transaction_date', '<=', $data['to']);
                        }

                        return $query;
                    }),
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
            'index'  => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit'   => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
