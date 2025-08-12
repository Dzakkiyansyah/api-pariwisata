<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function store(Request $request, Destination $destination)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            // Aturan validasi unik: pastikan user_id dan destination_id belum pernah ada bersamaan
            'user_id' => Rule::unique('reviews')->where(function ($query) use ($user, $destination) {
                return $query->where('user_id', $user->id)
                             ->where('destination_id', $destination->id);
            }),
        ]);

        $review = $destination->reviews()->create([
            'user_id' => $user->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return response()->json($review, 201);
    }
}
