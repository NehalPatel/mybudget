<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalExpensesWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Expenses', number_format($this->getTotalExpenses(), 2))
                ->description('Total Expenses')
                ->descriptionIcon('heroicon-s-arrow-trending-up')
                ->color('warning'),

            Stat::make('Incomes', number_format($this->getTotalIncomes(), 2))
                ->description('Total Incomes')
                ->descriptionIcon('heroicon-s-arrow-trending-down')
                ->color('success'),
        ];
    }

    protected function getTotalExpenses(): string
    {
        return Transaction::where('transaction_type', 'expense')->sum('amount');
    }

    protected function getTotalIncomes(): string
    {
        return Transaction::where('transaction_type', 'income')->sum('amount');
    }
}
