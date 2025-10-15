<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Get all reviews for a specific restaurant
     */
    public function index(Restaurant $restaurant)
    {
        $reviews = $restaurant->reviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reviews);
    }

    /**
     * Create a new review for a restaurant
     */
    public function store(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user already reviewed this restaurant
        $existingReview = Review::where('user_id', $request->user()->id)
            ->where('restaurant_id', $restaurant->id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'message' => 'You have already reviewed this restaurant.'
            ], 422);
        }

        // Create the review
        $review = Review::create([
            'user_id' => $request->user()->id,
            'restaurant_id' => $restaurant->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Load the user relationship for the response
        $review->load('user');

        // Update restaurant's average rating
        $this->updateRestaurantRating($restaurant);

        return response()->json($review, 201);
    }

    /**
     * Update the restaurant's average rating
     */
    private function updateRestaurantRating(Restaurant $restaurant)
    {
        $averageRating = $restaurant->reviews()->avg('rating');
        $restaurant->update([
            'rating' => round($averageRating, 1)
        ]);
    }
}
