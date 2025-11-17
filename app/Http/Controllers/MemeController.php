<?php

namespace App\Http\Controllers;

use App\Models\Meme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MemeController extends Controller
{
    /**
     * Show the meme editor page
     */
    public function editor()
    {
        return view('meme-editor');
    }

    /**
     * Show the GIF creator page
     */
    public function gifCreator()
    {
        return view('gif-creator');
    }

    /**
     * Display a listing of the user's memes.
     */
    public function index()
    {
        $memes = Meme::where('user_id', Auth::id())->latest()->get();
        return view('memes.index', ['memes' => $memes]);
    }

    /**
     * Store a newly created meme in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'data' => 'required|json',
        ]);

        $meme = Meme::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'type' => 'meme',
            'data' => $request->data,
        ]);

        return redirect()->route('memes.index')->with('status', 'Meme saved successfully!');
    }

    /**
     * Save image file (meme or GIF) to storage
     */
    public function saveImage(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:meme,gif',
            'image' => 'required|string', // Base64 encoded image
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            // Decode base64 image
            $imageData = $request->input('image');

            // Remove data:image/...;base64, prefix if exists
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $extension = $matches[1];
            } else {
                $extension = 'png';
            }

            $imageData = base64_decode($imageData);

            // Generate unique filename
            $filename = Str::random(40) . '.' . $extension;
            $path = $request->type . 's/' . date('Y/m/');

            // Store file
            Storage::disk('public')->put($path . $filename, $imageData);

            // Save to database
            $meme = Meme::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'type' => $request->type,
                'image_path' => $path . $filename,
                'description' => $request->description,
                'data' => json_encode([
                    'saved_at' => now(),
                    'settings' => $request->input('settings', [])
                ]),
            ]);

            return response()->json([
                'success' => true,
                'message' => ucfirst($request->type) . ' đã được lưu thành công!',
                'meme' => $meme,
                'url' => Storage::url($path . $filename)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lưu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a specific meme
     */
    public function show($id)
    {
        $meme = Meme::where('user_id', Auth::id())->findOrFail($id);
        return view('memes.show', compact('meme'));
    }

    /**
     * Delete a meme
     */
    public function destroy($id)
    {
        $meme = Meme::where('user_id', Auth::id())->findOrFail($id);

        // Delete image file if exists
        if ($meme->image_path) {
            Storage::disk('public')->delete($meme->image_path);
        }

        $meme->delete();

        return redirect()->route('memes.index')->with('status', 'Đã xóa thành công!');
    }
}