<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Yaminee', 'Devashya', 'Zomato', 'Swiggy', 'Chinmay', 'i20', 'SDJIC'
        ];

        foreach ($tags as $key => $tag) {
            \App\Models\Tag::create([
                'name' => $tag,
                'slug' => Str::slug($tag),
            ]);
        }
    }
}
