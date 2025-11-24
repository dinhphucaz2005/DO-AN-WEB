<?php

namespace App\Http\Controllers;

use App\Models\Meme;
use App\Models\User;
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
    public function index(Request $request)
    {
        $query = Meme::where('user_id', Auth::id());

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by visibility
        if ($request->filled('visibility') && $request->visibility !== 'all') {
            $isPublic = $request->visibility === 'public';
            $query->where('is_public', $isPublic);
        }

        // Sort
        $sort = $request->get('sort', 'date_desc');
        switch ($sort) {
            case 'date_asc':
                $query->oldest();
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            default: // date_desc
                $query->latest();
        }

        $memes = $query->get();
        return view('memes.index', ['memes' => $memes]);
    }

    /**
     * Display a listing of the user's memes.
     */
    public function toggleLike($id)
    {
        $meme = Meme::findOrFail($id);
        $user = Auth::user();

        $existingLike = $meme->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            $meme->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'count' => $meme->likes()->count()
        ]);
    }

    public function publish($id)
    {
        $meme = Meme::where('user_id', Auth::id())->findOrFail($id);
        $meme->update(['is_public' => true]);

        return back()->with('status', 'Tác phẩm đã được đăng công khai! 🌍');
    }

    public function publicIndex()
    {
        $memes = Meme::where('is_public', true)->latest()->paginate(12);
        return view('memes.public', ['memes' => $memes]);
    }

    /**
     * Display public posts of a specific user.
     */
    public function userPosts($userId)
    {
        $user = User::findOrFail($userId);
        $memes = Meme::where('user_id', $userId)->where('is_public', true)->latest()->get();
        return view('memes.user', ['memes' => $memes, 'user' => $user]);
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
            'is_public' => 'sometimes|boolean',
        ]);

        try {
            // Decode base64 image
            $imageData = $request->input('image');

            // Remove data:image/...;base64, prefix if exists
            $mimeType = 'image/png'; // default
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $extension = $matches[1];
                $mimeType = 'image/' . $extension;
            }

            // Validate base64 string
            $decoded = base64_decode($imageData, true);
            if ($decoded === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu hình ảnh không hợp lệ (không phải base64).'
                ], 422);
            }

            // Save to database with blob data
            $meme = Meme::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'type' => $request->type,
                'image_data' => $decoded,
                'mime_type' => $mimeType,
                'description' => $request->description,
                'is_public' => $request->input('is_public', false),
                'data' => json_encode([
                    'saved_at' => now(),
                    'settings' => $request->input('settings', [])
                ]),
            ]);

            return response()->json([
                'success' => true,
                'message' => ucfirst($request->type) . ' đã được lưu thành công!',
                'meme_id' => $meme->id,
                'url' => route('memes.image', $meme->id)
            ]);

        } catch (\Exception $e) {
            // Log full exception for debugging
            logger()->error('Error saving image: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lưu: Vui lòng thử lại sau hoặc kiểm tra logs.'
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
        $meme->delete();

        return redirect()->route('memes.index')->with('status', 'Đã xóa thành công!');
    }

    /**
     * Serve image from database
     */
    public function serveImage($id)
    {
        $meme = Meme::findOrFail($id);
        
        if (!$meme->image_data) {
            abort(404);
        }

        return response($meme->image_data)
            ->header('Content-Type', $meme->mime_type ?? 'image/png')
            ->header('Cache-Control', 'public, max-age=31536000');
    }
}

