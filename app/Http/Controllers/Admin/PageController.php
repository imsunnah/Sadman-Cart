<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->paginate(10);
        return Inertia::render('Admin/Page/Index', [
            'pages' => $pages
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Page/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content_en' => 'required|string',
            'content_bn' => 'nullable|string',
            'group' => 'required|string|in:about_us,consumer_policy,support',
            'is_active' => 'required|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title_en']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/gallery', 'public');
            $validated['image'] = '/storage/' . $path;
        }

        Page::create($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        return Inertia::render('Admin/Page/Edit', [
            'page' => $page
        ]);
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content_en' => 'required|string',
            'content_bn' => 'nullable|string',
            'group' => 'required|string|in:about_us,consumer_policy,support',
            'is_active' => 'required|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title_en']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/gallery', 'public');
            $validated['image'] = '/storage/' . $path;
        } else {
            unset($validated['image']);
        }

        $page->update($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }

    public function toggleActive(Page $page)
    {
        $page->update(['is_active' => !$page->is_active]);
        return redirect()->back()->with('success', 'Page status updated successfully.');
    }
}
