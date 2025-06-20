<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

class ProductCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         ProductCategory::create([
            'Name' => 'قلائد',
            'Description' => 'مجموعة رائعة من القلائد الذهبية.',
            'CategoryFile' => 'uploads/product_categories/1747829094_photo_2024-10-20_13-53-58.jpg',
            'smithing'=>10
        ]);

        ProductCategory::create([
            'Name' => 'خواتم',
            'Description' => 'خواتم أنيقة بأشكال مميزة.',
            'CategoryFile' => 'uploads/product_categories/1747829471_photo_2024-10-20_13-53-53.jpg',
            'smithing'=>20
        ]);
    }
}
