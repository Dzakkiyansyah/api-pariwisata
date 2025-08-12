<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        return News::with('user')->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        $news = News::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'image_path' => $imagePath,
            'user_id' => Auth::id(),
        ]);

        return response()->json($news, 201);
    }

    public function show(News $news)
    {
        return $news->load('user');
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            // Storage::disk('public')->delete($news->image_path);
            $validated['image_path'] = $request->file('image')->store('news_images', 'public');
        }

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $news->update($validated);

        return response()->json($news);
    }

    public function destroy(News $news)
    {
        // Hapus gambar terkait jika ada
        // Storage::disk('public')->delete($news->image_path);
        $news->delete();

        return response()->json(['message' => 'Berita berhasil dihapus.']);
    }
}
