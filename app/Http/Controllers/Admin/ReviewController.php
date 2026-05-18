<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::latest()->paginate(15);
        return Inertia::render('Admin/Reviews/Index', ['reviews' => $reviews]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'comment'       => 'required|string',
            'rating'        => 'required|integer|min:1|max:5',
            'is_active'     => 'nullable|boolean',
        ]);

        Review::create([
            'customer_name' => $request->customer_name,
            'comment'       => $request->comment,
            'rating'        => $request->rating,
            'is_active'     => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('admin.reviews.index')->with('success', 'Review added successfully.');
    }

    public function update(Request $request, Review $review)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'comment'       => 'required|string',
            'rating'        => 'required|integer|min:1|max:5',
            'is_active'     => 'nullable|boolean',
        ]);

        $review->update([
            'customer_name' => $request->customer_name,
            'comment'       => $request->comment,
            'rating'        => $request->rating,
            'is_active'     => $request->has('is_active') ? (bool)$request->is_active : $review->is_active,
        ]);
        return redirect()->route('admin.reviews.index')->with('success', 'Review updated.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted.');
    }

    public function toggleActive(Review $review)
    {
        $review->update(['is_active' => !$review->is_active]);
        return back()->with('success', 'Review status updated.');
    }
}
