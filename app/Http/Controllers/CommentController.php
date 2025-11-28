<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Meme;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Lưu bình luận mới
    public function store(Request $request, $memeId)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'meme_id' => $memeId,
            'content' => $request->content,
        ]);

        // Trả về bình luận mới để render lại giao diện
        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'user' => $comment->user->name,
                'content' => $comment->content,
                'created_at' => $comment->created_at->diffForHumans(),
            ]
        ]);
    }
}
