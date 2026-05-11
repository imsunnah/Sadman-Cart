<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->query('category_id');
        
        $query = Product::with(['category', 'subCategory', 'gallery'])->latest();
        
        if ($categoryId && $categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }
        
        $products = $query->paginate(10)->withQueryString();
        $categories = Category::all();
        
        return Inertia::render('Admin/Product/Index', [
            'products' => $products,
            'categories' => $categories,
            'currentCategoryId' => $categoryId ?? 'all'
        ]);
    }

    public function create()
    {
        $categories = Category::with('subCategories')->get();
        return Inertia::render('Admin/Product/Create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'buying_price' => 'required|numeric|min:0',
            'package_cost' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
            'discount_type' => 'nullable|string|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['sku'] = 'PRD-' . strtoupper(substr(uniqid(), -5));
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/products', 'public');
            $validated['image'] = \Illuminate\Support\Facades\Storage::disk('public')->url($path);
        }

        $product = Product::create($validated);

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $path = $file->store('uploads/products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => \Illuminate\Support\Facades\Storage::disk('public')->url($path)
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product added successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::with('subCategories')->get();
        return Inertia::render('Admin/Product/Edit', [
            'product' => $product->load('gallery'),
            'categories' => $categories
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'buying_price' => 'required|numeric|min:0',
            'package_cost' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
            'discount_type' => 'nullable|string|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/products', 'public');
            $validated['image'] = \Illuminate\Support\Facades\Storage::disk('public')->url($path);
        }

        $product->update($validated);

        // Handle Gallery Image Removals
        $keepImages = $request->input('keep_gallery_images', []); // Array of Image IDs to keep
        $product->gallery()->whereNotIn('id', $keepImages)->delete();

        // Handle New Gallery Images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $path = $file->store('uploads/products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => \Illuminate\Support\Facades\Storage::disk('public')->url($path)
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return redirect()->back()->with('success', 'Product status updated successfully.');
    }
}
