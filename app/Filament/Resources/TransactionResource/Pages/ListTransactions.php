<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Employee;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => Tab::make(),
            'month_expense' => Tab::make('This Month Expenses')
                ->modifyQueryUsing(
                    fn (Builder $query) =>
                    $query->where('transaction_date', '>=', now()->subMonth())
                        ->where('transaction_type', 'expense')
                )
                ->badgeIcon('heroicon-m-banknotes')
                ->badgeColor('danger')
                ->badge(
                    Transaction::query()
                        ->where('transaction_date', '>=', now()->subMonth())
                        ->where('transaction_type', 'expense')
                        ->count()
                ),

            'month_income' => Tab::make('This Month Income')
                ->modifyQueryUsing(
                    fn (Builder $query) =>
                    $query->where('transaction_date', '>=', now()->subMonth())
                        ->where('transaction_type', 'income')
                )
                ->badgeIcon('heroicon-m-currency-rupee')
                ->badgeColor('success')
                ->badge(
                    Transaction::query()
                        ->where('transaction_date', '>=', now()->subMonth())
                        ->where('transaction_type', 'income')
                        ->count()
                ),

            'week_expense' => Tab::make('This Week Expenses')
                ->modifyQueryUsing(
                    fn (Builder $query) =>
                    $query->where('transaction_date', '>=', now()->subWeek())
                        ->where('transaction_type', 'expense')
                )
                ->badgeIcon('heroicon-m-banknotes')
                ->badgeColor('danger')
                ->badge(
                    Transaction::query()
                        ->where('transaction_date', '>=', now()->subWeek())
                        ->where('transaction_type', 'expense')
                        ->count()
                ),

            'week_income' => Tab::make('This Week Income')
                ->modifyQueryUsing(
                    fn (Builder $query) =>
                    $query->where('transaction_date', '>=', now()->subWeek())
                        ->where('transaction_type', 'income')
                )
                ->badgeIcon('heroicon-m-banknotes')
                ->badgeColor('danger')
                ->badge(
                    Transaction::query()
                        ->where('transaction_date', '>=', now()->subWeek())
                        ->where('transaction_type', 'income')
                        ->count()
                ),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TransactionResource\Widgets\TotalExpensesWidget::class
        ];
    }
}
