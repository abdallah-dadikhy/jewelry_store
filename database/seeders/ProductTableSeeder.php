<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'Name'=>'خاتم الماس',
            'Description'=>'جميلة جدا ',
            'Weight'=>22,
            'Price'=>12,
            'ProductFile'=>'uploads/product_categories/1747829094_photo_2024-10-20_13-53-58.jpg',
            'CategoryID'=>1
        ]);
        Product::create([
            'Name'=>'خاتم الماس',
            'Description'=>'جميلة جدا ',
            'Weight'=>22,
            'Price'=>12,
            'ProductFile'=>'uploads/product_categories/1747829094_photo_2024-10-20_13-53-58.jpg',
            'CategoryID'=>1
        ]);
        
        Product::create([
            'Name'=>'قلادة الماس',
            'Description'=>'جميلة جدا ',
            'Weight'=>22,
            'Price'=>12,
            'ProductFile'=>'uploads/product_categories/1747829094_photo_2024-10-20_13-53-58.jpg',
            'CategoryID'=>1
        ]);
        Product::create([
            'Name'=>'قلادة الماس',
            'Description'=>'جميلة جدا ',
            'Weight'=>22,
            'Price'=>12,
            'ProductFile'=>'uploads/product_categories/1747829094_photo_2024-10-20_13-53-58.jpg',
            'CategoryID'=>1
        ]);
    }
}
