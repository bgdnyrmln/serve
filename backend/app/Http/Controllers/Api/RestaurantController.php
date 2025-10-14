<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $query = Restaurant::query()->with('owner');

        $search = trim((string) $request->query('q', ''));
        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function show(Restaurant $restaurant)
    {
        $restaurant->load('owner');
        return response()->json($restaurant);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'cuisine' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $restaurant = Restaurant::create(array_merge($data, [
            'owner_id' => $request->user()->id,
            'open' => true,
        ]));

        return response()->json($restaurant, 201);
    }


    public function destroy($id)
    {
        $restaurant = Restaurant::find($id);

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        try {
            $restaurant->delete();
            return response()->json(['message' => 'Restaurant deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting restaurant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'cuisine' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'open' => 'nullable|boolean',
        ]);

        $restaurant->update($data);

        return response()->json($restaurant);
    }
}
