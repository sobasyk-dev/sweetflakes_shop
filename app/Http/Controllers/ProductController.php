<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;

class ProductController extends Controller
{
    public function ad_inventory() {
        // Use Eager Loading to get variants with the product
        $products = Product::with('variants')->get();
        return view('admin.ad_inventory', compact('products'));
    }

    public function ad_create() {
        $categories = Category::where('is_active', true)->get();
        
        // Create an empty instance so $product exists in the view
        $product = new Product(); 
        
        return view('admin.ad_create', compact('categories', 'product'));
    }

    public function ad_store(Request $request) {
        $request->validate([
            'name' => 'required|unique:products,name',
            'category_id' => 'required',
            'variants' => 'required|array|min:1',
            'new_category_name' => 'required_if:category_id,NEW|nullable|unique:categories,name', 
        ]);

        return DB::transaction(function () use ($request) {
            $categoryId = $request->category_id;

            if ($categoryId === 'NEW') {
                $category = Category::create([
                    'name' => $request->new_category_name,
                    'slug' => Str::slug($request->new_category_name),
                    'is_active' => true
                ]);
                $categoryId = $category->id;
            }

            // LOGIC: Product is active if ANY variant is checked active
            $productActive = collect($request->variants)->contains('is_active', '1');

            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_id' => $categoryId,
                'description' => $request->description,
                'image' => $request->image,
                'is_active' => $productActive, // Now calculated from variants
            ]);

            foreach ($request->variants as $index => $variantData) {
                $product->variants()->create([
                    'name' => $variantData['name'],
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'],
                    'sku' => $variantData['sku'] ?? null,
                    'is_active' => isset($variantData['is_active']) && $variantData['is_active'] == '1',
                    'is_default' => ($index === 0), 
                ]);
            }

            return redirect()->route('admin.ad_inventory')->with('success', 'Product & Variants created!');
        });
    }

    public function ad_edit(Product $product) {
        // Load categories and the default variant for editing
        $categories = Category::all();
        $defaultVariant = $product->variants()->where('is_default', true)->first();
        
        return view('admin.ad_edit', compact('product', 'categories', 'defaultVariant'));
    }

    public function ad_update(Product $product, Request $request) {
        return DB::transaction(function () use ($product, $request) {
            // 1. Resolve Category
            $categoryId = $request->category_id;
            if ($categoryId === 'NEW') {
                $categoryId = $this->createNewCategory($request->new_category_name);
            }

            // 2. LOGIC: Calculate Product visibility based on Variants
            // This prevents the "cannot be null" error
            $productActive = collect($request->variants)->contains('is_active', '1');

            // 3. Update Product Details
            $product->update([
                'name' => $request->name,
                'category_id' => $categoryId,
                'description' => $request->description,
                'image' => $request->image,
                'is_active' => $productActive, 
            ]);

            // 4. Handle Variants
            $incomingVariantIds = collect($request->variants)->pluck('id')->filter()->toArray();
            
            // Delete removed variants
            $product->variants()->whereNotIn('id', $incomingVariantIds)->where('is_default', false)->delete();

            foreach ($request->variants as $vData) {
                $variantPayload = [
                    'name' => $vData['name'],
                    'price' => $vData['price'],
                    'stock' => $vData['stock'],
                    'is_active' => isset($vData['is_active']) && $vData['is_active'] == '1',
                ];

                if (isset($vData['id'])) {
                    // Update existing variant
                    $product->variants()->where('id', $vData['id'])->update($variantPayload);
                } else {
                    // Create new variant
                    $variantPayload['is_default'] = false;
                    $product->variants()->create($variantPayload);
                }
            }

            return redirect()->route('admin.ad_inventory')->with('update', 'Product and Variants updated!');
        });
    }

    /**
     * Helper method to handle new category creation
     * Place this at the bottom of your controller class
     */
    private function createNewCategory($name) {
        // Generate a slug (e.g., "Artisan Cakes" becomes "artisan-cakes")
        $slug = Str::slug($name);

        $category = \App\Models\Category::firstOrCreate(
            ['slug' => $slug], // Search by slug to avoid duplicates
            ['name' => ucwords(strtolower($name))] // If not found, create with this name
        );
        
        return $category->id;
    }

    public function ad_delete(Product $product) 
    {
        return DB::transaction(function () use ($product) {
            // 1. Delete associated variants first (if not using cascade delete in migration)
            $product->variants()->delete();

            // 2. Delete the product
            $product->delete();

            return redirect()->route('admin.ad_inventory')
                ->with('deleted', 'Creation removed from the lab inventory.');
        });
    }
}