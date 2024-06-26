<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $income_categories = [
            'Salary','Bonus','Freelance','Investment','Rental','Gifts','Interest','Tax Refund','Miscellaneous Income'
        ];

        foreach ($income_categories as $key => $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'type' => 'income',
            ]);
        }

        $expense_categories = [
            'Rent','Property Taxes','Electricity','Gas','Maintenance and Repairs','Fuel','Public Transportation','Car Insurance','Food-Groceries','Food','Internet','TV DTH','Mobile Bill','Life Insurance','Healthcare','Debt Payments','Investment','Entertainment','Personal Care','Shopping','Clothing','Miscellaneous'
        ];

        foreach ($expense_categories as $key => $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'type' => 'expense',
            ]);
        }
    }
}
