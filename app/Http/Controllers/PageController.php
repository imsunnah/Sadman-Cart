<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Inertia\Inertia;

class PageController extends Controller
{
    public function show(Page $page)
    {
        if (!$page->is_active) {
            abort(404);
        }

        return Inertia::render('PageShow', [
            'page' => $page
        ]);
    }
}
