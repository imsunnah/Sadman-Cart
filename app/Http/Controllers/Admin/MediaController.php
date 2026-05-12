<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class MediaController extends Controller
{
    public function index()
    {
        $files = $this->getAllMedia();
        return Inertia::render('Admin/Gallery/Index', [
            'media' => $files
        ]);
    }

    public function apiIndex()
    {
        return response()->json($this->getAllMedia());
    }

    private function getAllMedia()
    {
        $files = [];
        $directories = ['uploads', 'combos'];
        
        foreach ($directories as $dir) {
            if (Storage::disk('public')->exists($dir)) {
                $allFiles = Storage::disk('public')->allFiles($dir);
                foreach ($allFiles as $file) {
                    if (in_array(File::extension($file), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
                        $files[] = [
                            'name' => basename($file),
                            'path' => $file,
                            'url' => '/storage/' . $file,
                            'size' => Storage::disk('public')->size($file),
                            'last_modified' => Storage::disk('public')->lastModified($file),
                        ];
                    }
                }
            }
        }

        // Sort by last modified descending
        usort($files, function($a, $b) {
            return $b['last_modified'] <=> $a['last_modified'];
        });

        return $files;
    }

    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120'
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $file->store('uploads/gallery', 'public');
            }
        }

        return back()->with('success', 'Media uploaded successfully.');
    }

    public function destroy(Request $request)
    {
        $path = $request->path;
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return back()->with('success', 'File deleted successfully.');
        }
        return back()->with('error', 'File not found.');
    }
}
