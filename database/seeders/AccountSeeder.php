<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            'Cash', 'PNB', 'Kotak Bank'
        ];

        foreach ($accounts as $key => $account) {
            \App\Models\Account::create([
                'name' => $account,
                'slug' => Str::slug($account),
            ]);
        }
    }
}
