<?php

namespace App\Http\Controllers;

use App\Models\Meme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'data' => $request->data,
        ]);

        return redirect()->route('memes.index')->with('status', 'Meme saved successfully!');
    }
}