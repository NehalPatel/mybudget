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
            Stat::make('Total Expenses', $total_expenses)
                ->description('Total Expense')
                ->descriptionIcon('heroicon-s-arrow-trending-up')
                ->color('warning'),
            Stat::make('Total Income', $total_income)
                ->description('Total Income')
                ->descriptionIcon('heroicon-s-arrow-trending-down')
                ->color('success'),

            Stat::make('Current Month Expenses', $current_month_expenses)
                ->description(Carbon::now()->monthName)
                ->descriptionIcon('heroicon-s-arrow-trending-up')
                ->color('warning'),

            Stat::make('Current Month Income', $current_month_income)
                ->description(Carbon::now()->monthName)
                ->descriptionIcon('heroicon-s-arrow-trending-down')
                ->color('success'),

            Stat::make('Previous Month Expenses', $previous_month_expenses)
                ->description(Carbon::now()->subMonth()->monthName)
                ->descriptionIcon('heroicon-s-arrow-trending-up')
                ->color('warning'),

            Stat::make('Previous Month Income', $previous_month_income)
                ->description(Carbon::now()->subMonth()->monthName)
                ->descriptionIcon('heroicon-s-arrow-trending-down')
                ->color('success'),
        ];
    }
}
