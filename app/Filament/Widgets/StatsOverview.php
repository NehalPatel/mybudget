<?php

namespace App\Filament\Widgets;

use App\Models\Contact;
use App\Models\Event;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $total_expenses = Transaction::where('transaction_type', 'expense')->sum('amount');
        $total_income = Transaction::where('transaction_type', 'income')->sum('amount');

        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        $current_month_expenses = Transaction::where('transaction_type', 'expense')
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->sum('amount');

        $current_month_income = Transaction::where('transaction_type', 'income')
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->sum('amount');

        $startOfPreviousMonth = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $endOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString();

        $previous_month_expenses = Transaction::where('transaction_type', 'expense')
            ->whereBetween('transaction_date', [$startOfPreviousMonth, $endOfPreviousMonth])->sum('amount');

        $previous_month_income = Transaction::where('transaction_type', 'income')
            ->whereBetween('transaction_date', [$startOfPreviousMonth, $endOfPreviousMonth])->sum('amount');

        return [
            Stat::make('Current Month Expenses', number_format($current_month_expenses, 2))
                ->description(Carbon::now()->monthName)
                ->descriptionIcon('heroicon-s-arrow-trending-up')
                ->color('warning'),

            Stat::make('Current Month Income', number_format($current_month_income,2))
                ->description(Carbon::now()->monthName)
                ->descriptionIcon('heroicon-s-arrow-trending-down')
                ->color('success'),

            Stat::make('Previous Month Expenses', number_format($previous_month_expenses, 2))
                ->description(Carbon::now()->subMonth()->monthName)
                ->descriptionIcon('heroicon-s-arrow-trending-up')
                ->color('warning'),

            Stat::make('Previous Month Income', number_format($previous_month_income,2))
                ->description(Carbon::now()->subMonth()->monthName)
                ->descriptionIcon('heroicon-s-arrow-trending-down')
                ->color('success'),

            Stat::make('Total Expenses', number_format($total_expenses,2 ))
                ->description('Total Expense')
                ->descriptionIcon('heroicon-s-arrow-trending-up')
                ->color('warning'),

            Stat::make('Total Income', number_format($total_income,2 ))
                ->description('Total Income')
                ->descriptionIcon('heroicon-s-arrow-trending-down')
                ->color('success'),
        ];
    }
}
