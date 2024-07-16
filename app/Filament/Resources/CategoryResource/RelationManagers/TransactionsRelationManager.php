<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                TextColumn::make('transaction_date')
                    ->sortable()
                    ->date('F j, Y'),

                TextColumn::make('transaction_type')
                    ->label('Type')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
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
                    ->alignment(Alignment::End)
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                ///Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
