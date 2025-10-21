<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Restaurant;
use App\Models\Reservation;
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
     * Determine if the authenticated user can leave a review for the restaurant
     */
    public function eligibility(Request $request, Restaurant $restaurant)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'canReview' => false,
                'message' => 'Authentication required.'
            ], 200);
        }

        // Owners cannot review their own restaurant
        if ((int) $restaurant->owner_id === (int) $user->id) {
            return response()->json([
                'canReview' => false,
                'message' => 'Owners cannot review their own restaurant.'
            ]);
        }

        // Ensure at least one confirmed reservation exists for this user at this restaurant
        $hasConfirmedReservation = Reservation::where('restaurant_id', $restaurant->id)
            ->where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->exists();

        if (!$hasConfirmedReservation) {
            return response()->json([
                'canReview' => false,
                'message' => 'You can only leave a review after having an accepted reservation at this restaurant.'
            ]);
        }

        // Check if the user has already left a review
        $alreadyReviewed = Review::where('user_id', $user->id)
            ->where('restaurant_id', $restaurant->id)
            ->exists();

        if ($alreadyReviewed) {
            return response()->json([
                'canReview' => false,
                'message' => 'You have already reviewed this restaurant.'
            ]);
        }

        return response()->json([
            'canReview' => true
        ]);
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

        // Ensure the authenticated user has at least one confirmed reservation at this restaurant
        $hasConfirmedReservation = Reservation::where('restaurant_id', $restaurant->id)
            ->where('user_id', $request->user()->id)
            ->where('status', 'confirmed')
            ->exists();

        if (!$hasConfirmedReservation) {
            return response()->json([
                'message' => 'You can only leave a review after having an accepted reservation at this restaurant.'
            ], 403);
        }

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
