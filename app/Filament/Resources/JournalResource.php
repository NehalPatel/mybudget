<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JournalResource\Pages;
use App\Filament\Resources\JournalResource\RelationManagers;
use App\Models\Journal;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JournalResource extends Resource
{
    protected static ?string $model = Journal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('description')
                    ->required()
                    ->label('Description'),
                DatePicker::make('transaction_date')
                    ->required()
                    ->label('Transaction Date'),
                Select::make('from_account_id')
                    ->relationship('FromAccount', 'name')
                    ->required()
                    ->label('From Account'),
                Select::make('to_account_id')
                    ->relationship('ToAccount', 'name')
                    ->required()
                    ->label('To Account'),
                TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->label('Amount')
                    ->prefix('₹'),
                FileUpload::make('attachments')
                    ->label('Attachments')
                    ->multiple()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')->sortable()->searchable(),
                TextColumn::make('transaction_date')->dateTime('F j, Y')->sortable(),
                TextColumn::make('fromAccount.name')->label('From Account')->sortable()->searchable(),
                TextColumn::make('toAccount.name')->label('To Account')->sortable()->searchable(),
                TextColumn::make('amount')->sortable()->prefix('₹'),
            ])
            ->filters([
                Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('from')->label('From Date'),
                        DatePicker::make('to')->label('To Date'),
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['from']) {
                            $query->whereDate('transaction_date', '>=', $data['from']);
                        }
                        if ($data['to']) {
                            $query->whereDate('transaction_date', '<=', $data['to']);
                        }

                        return $query;
                    }),
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
            'index' => Pages\ListJournals::route('/'),
            'create' => Pages\CreateJournal::route('/create'),
            'edit' => Pages\EditJournal::route('/{record}/edit'),
        ];
    }
}
