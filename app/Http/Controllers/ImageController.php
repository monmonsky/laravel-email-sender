<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ImageController extends Controller
{
    public function index()
    {
        $images = [];
        $path = public_path('images/emails');

        if (is_dir($path)) {
            $files = array_diff(scandir($path), ['.', '..']);
            foreach ($files as $file) {
                if (is_file($path . '/' . $file)) {
                    $images[] = [
                        'name' => $file,
                        'url' => asset('images/emails/' . $file),
                        'size' => filesize($path . '/' . $file),
                        'created_at' => date('Y-m-d H:i:s', filectime($path . '/' . $file)),
                    ];
                }
            }
        }

        return Inertia::render('Images/Index', [
            'images' => $images,
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/emails'), $filename);

            return response()->json([
                'success' => true,
                'url' => asset('images/emails/' . $filename),
                'filename' => $filename,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No image uploaded',
        ], 400);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
        ]);

        $path = public_path('images/emails/' . $request->filename);

        if (file_exists($path)) {
            unlink($path);
            return redirect()->route('images.index')
                ->with('success', 'Image deleted successfully!');
        }

        return redirect()->route('images.index')
            ->with('error', 'Image not found!');
    }
}
