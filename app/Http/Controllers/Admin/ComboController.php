<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ComboController extends Controller
{
    public function index()
    {
        $combos = Combo::with('products')->latest()->paginate(10);
        return Inertia::render('Admin/Combo/Index', ['combos' => $combos]);
    }

    public function create()
    {
        $products = Product::where('is_active', true)->get();
        return Inertia::render('Admin/Combo/Create', ['products' => $products]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'original_price' => 'nullable|numeric',
            'image' => 'nullable',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('product_ids', 'image');
        $data['slug'] = Str::slug($request->name) . '-' . time();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('combos', 'public');
            $data['image'] = '/storage/' . $path;
        } elseif (is_string($request->image) && str_starts_with($request->image, '/storage/')) {
            $data['image'] = $request->image;
        }

        $combo = Combo::create($data);
        $combo->products()->attach($request->product_ids);

        return redirect()->route('admin.combos.index')->with('success', 'Combo created successfully.');
    }

    public function edit(Combo $combo)
    {
        $combo->load('products');
        $products = Product::where('is_active', true)->get();
        return Inertia::render('Admin/Combo/Edit', [
            'combo' => $combo,
            'products' => $products
        ]);
    }

    public function update(Request $request, Combo $combo)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'original_price' => 'nullable|numeric',
            'image' => 'nullable',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('product_ids', 'image');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('combos', 'public');
            $data['image'] = '/storage/' . $path;
        } elseif ($request->filled('image') && is_string($request->image)) {
            $data['image'] = $request->image;
        } else {
            // Do not overwrite image if not provided
            unset($data['image']);
        }

        $combo->update($data);
        $combo->products()->sync($request->product_ids);

        return redirect()->route('admin.combos.index')->with('success', 'Combo updated successfully.');
    }

    public function destroy(Combo $combo)
    {
        if ($combo->image) {
            $oldPath = str_replace(Storage::disk('public')->url(''), '', $combo->image);
            Storage::disk('public')->delete($oldPath);
        }
        $combo->delete();
        return redirect()->route('admin.combos.index')->with('success', 'Combo deleted successfully.');
    }

    public function toggleActive(Combo $combo)
    {
        $combo->update(['is_active' => !$combo->is_active]);
        return back()->with('success', 'Combo status updated.');
    }
}
