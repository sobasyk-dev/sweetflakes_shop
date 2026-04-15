<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // === Cakes ===
        $cakes = Category::where('slug', 'cakes')->first();

        $chocolateCake = Product::create([
            'category_id' => $cakes->id,
            'name' => 'Chocolate Cake',
            'slug' => Str::slug('Chocolate Cake'),
            'description' => 'Rich and moist chocolate cake',
            'image' => 'products/chocolate-cake.jpg',
            'is_active' => true,
        ]);

        ProductVariant::insert([
            [
                'product_id' => $chocolateCake->id,
                'name' => '6 inch',
                'price' => 45.00,
                'stock' => 10,
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'product_id' => $chocolateCake->id,
                'name' => '8 inch',
                'price' => 65.00,
                'stock' => 5,
                'is_default' => false,
                'is_active' => true,
            ],
        ]);

        // === Cookies ===
        $cookies = Category::where('slug', 'cookies')->first();

        $chocCookie = Product::create([
            'category_id' => $cookies->id,
            'name' => 'Chocolate Chip Cookie',
            'slug' => Str::slug('Chocolate Chip Cookie'),
            'description' => 'Crunchy outside, chewy inside',
            'image' => 'products/choc-cookie.jpg',
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $chocCookie->id,
            'name' => 'Standard',
            'price' => 5.00,
            'stock' => 50,
            'is_default' => true,
            'is_active' => true,
        ]);

        // === Drinks ===
        $drinks = Category::where('slug', 'drinks')->first();

        $icedLatte = Product::create([
            'category_id' => $drinks->id,
            'name' => 'Iced Latte',
            'slug' => Str::slug('Iced Latte'),
            'description' => 'Cold brewed espresso with milk',
            'image' => 'products/iced-latte.jpg',
            'is_active' => true,
        ]);

        ProductVariant::insert([
            [
                'product_id' => $icedLatte->id,
                'name' => 'Small',
                'price' => 8.00,
                'stock' => 30,
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'product_id' => $icedLatte->id,
                'name' => 'Large',
                'price' => 10.00,
                'stock' => 20,
                'is_default' => false,
                'is_active' => true,
            ],
        ]);
    }
}
