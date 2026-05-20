<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Brand::with('categories')->latest();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name_en', 'like', '%' . $search . '%')
                  ->orWhere('name_bn', 'like', '%' . $search . '%');
            });
        }

        $brands = $query->paginate(10)->withQueryString();
        
        return Inertia::render('Admin/Brand/Index', [
            'brands' => $brands,
            'searchFilter' => $search ?? ''
        ]);
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name_en', 'asc')->get();
        return Inertia::render('Admin/Brand/Create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'image' => 'nullable',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'nullable|exists:categories,id'
        ]);

        $validated['slug'] = Str::slug($validated['name_en']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/gallery', 'public');
            $validated['image'] = '/storage/' . $path;
        } elseif (is_string($request->image)) {
            $validated['image'] = $request->image;
        }

        $brand = Brand::create($validated);

        if ($request->has('category_ids') && is_array($request->category_ids)) {
            $brand->categories()->sync($request->category_ids);
        }

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
    }

    public function edit(Brand $brand)
    {
        $categories = Category::where('is_active', true)->orderBy('name_en', 'asc')->get();
        return Inertia::render('Admin/Brand/Edit', [
            'brand' => $brand->load('categories'),
            'categories' => $categories
        ]);
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'image' => 'nullable',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'nullable|exists:categories,id'
        ]);

        $validated['slug'] = Str::slug($validated['name_en']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/gallery', 'public');
            $validated['image'] = '/storage/' . $path;
        } elseif ($request->filled('image') && is_string($request->image)) {
            $validated['image'] = $request->image;
        } else {
            unset($validated['image']);
        }

        $brand->update($validated);

        if ($request->has('category_ids') && is_array($request->category_ids)) {
            $brand->categories()->sync($request->category_ids);
        } else {
            $brand->categories()->detach();
        }

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully.');
    }

    public function toggleActive(Brand $brand)
    {
        $brand->update(['is_active' => !$brand->is_active]);
        return redirect()->back()->with('success', 'Brand status updated successfully.');
    }
}
