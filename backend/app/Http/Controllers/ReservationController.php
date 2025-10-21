<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class ReservationController extends Controller
{
    /**
     * Display all reservations for a restaurant (owner only).
     */
    public function restaurantReservations(Restaurant $restaurant)
    {
        $user = Auth::user();
        if ($restaurant->owner_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $reservations = $restaurant->reservations()->with('user')->orderBy('reservation_date', 'desc')->orderBy('reservation_time', 'desc')->get();
        return response()->json($reservations);
    }
    /**
     * Display a listing of the user's reservations.
     */
    public function index()
    {
        $user = Auth::user();
        $reservations = $user->reservations()
            ->with('restaurant')
            ->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->get();

        return response()->json($reservations);
    }

    /**
     * Store a newly created reservation in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required|date_format:H:i',
            'party_size' => 'required|integer|min:1|max:20',
            'special_requests' => 'nullable|string|max:500',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
        ]);

        // Check if restaurant is open
        $restaurant = Restaurant::findOrFail($request->restaurant_id);
        if (!$restaurant->open) {
            return response()->json([
                'message' => 'This restaurant is currently closed and not accepting reservations.'
            ], 422);
        }

        // Check for conflicting reservations (simple check - can be enhanced)
        $existingReservation = Reservation::where('restaurant_id', $request->restaurant_id)
            ->where('reservation_date', $request->reservation_date)
            ->where('reservation_time', $request->reservation_time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        if ($existingReservation >= 5) { // Allow up to 5 tables at the same time
            return response()->json([
                'message' => 'This time slot is fully booked. Please choose a different time.'
            ], 422);
        }

        $reservation = Reservation::create([
            'restaurant_id' => $request->restaurant_id,
            'user_id' => Auth::id(),
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'party_size' => $request->party_size,
            'special_requests' => $request->special_requests,
            'contact_phone' => $request->contact_phone ?: Auth::user()->phone,
            'contact_email' => $request->contact_email ?: Auth::user()->email,
            'status' => 'pending'
        ]);

        $reservation->load('restaurant');

        return response()->json([
            'message' => 'Reservation created successfully!',
            'reservation' => $reservation
        ], 201);
    }

    /**
     * Display the specified reservation.
     */
    public function show(Reservation $reservation)
    {
        // Ensure user can only see their own reservations
        if ($reservation->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reservation->load('restaurant');
        return response()->json($reservation);
    }

    /**
     * Update the specified reservation in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        // Ensure user can only update their own reservations
        if ($reservation->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Only allow updates if reservation is pending
        if ($reservation->status !== 'pending') {
            return response()->json([
                'message' => 'Only pending reservations can be modified.'
            ], 422);
        }

        $request->validate([
            'reservation_date' => 'sometimes|date|after_or_equal:today',
            'reservation_time' => 'sometimes|date_format:H:i',
            'party_size' => 'sometimes|integer|min:1|max:20',
            'special_requests' => 'nullable|string|max:500',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
        ]);

        $reservation->update($request->only([
            'reservation_date',
            'reservation_time',
            'party_size',
            'special_requests',
            'contact_phone',
            'contact_email'
        ]));

        $reservation->load('restaurant');

        return response()->json([
            'message' => 'Reservation updated successfully!',
            'reservation' => $reservation
        ]);
    }

    /**
     * Cancel the specified reservation.
     */
    public function destroy(Reservation $reservation)
    {
        // Ensure user can only cancel their own reservations
        if ($reservation->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Only allow cancellation if reservation is pending or confirmed
        if (!in_array($reservation->status, ['pending', 'confirmed'])) {
            return response()->json([
                'message' => 'This reservation cannot be cancelled.'
            ], 422);
        }

        $reservation->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Reservation cancelled successfully!'
        ]);
    }

    /**
     * Get available time slots for a restaurant on a specific date.
     */
    public function availableTimeSlots(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today'
        ]);

        if (!$restaurant->open) {
            return response()->json([
                'available_slots' => [],
                'message' => 'Restaurant is currently closed'
            ]);
        }

        // Generate time slots (11:00 AM to 9:00 PM every 30 minutes)
        $timeSlots = [];
        $startTime = 11; // 11 AM
        $endTime = 21;   // 9 PM

        for ($hour = $startTime; $hour < $endTime; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 30) {
                $timeSlots[] = sprintf('%02d:%02d', $hour, $minute);
            }
        }

        // Check availability for each slot
        $availableSlots = [];
        foreach ($timeSlots as $slot) {
            $reservationCount = Reservation::where('restaurant_id', $restaurant->id)
                ->where('reservation_date', $request->date)
                ->where('reservation_time', $slot)
                ->whereIn('status', ['pending', 'confirmed'])
                ->count();

            if ($reservationCount < 5) { // Assuming 5 tables available per slot
                $availableSlots[] = [
                    'time' => $slot,
                    'available_tables' => 5 - $reservationCount
                ];
            }
        }

        return response()->json([
            'available_slots' => $availableSlots
        ]);
    }

    /**
     * Download reservation as PDF.
     */
    public function downloadPdf(Reservation $reservation)
    {
        // Ensure user can only download their own reservations
        if ($reservation->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reservation->load('restaurant', 'user');

        $pdf = PDF::loadView('pdf.reservation', compact('reservation'));

        $filename = 'reservation-' . $reservation->id . '-' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Accept a reservation (restaurant owner only).
     */
    public function accept(Reservation $reservation)
    {
        $user = Auth::user();
        $restaurant = $reservation->restaurant;

        // Check if user is the owner of the restaurant
        if ($restaurant->owner_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized. Only the restaurant owner can accept reservations.'], 403);
        }

        // Check if reservation is in pending status
        if ($reservation->status !== 'pending') {
            return response()->json([
                'message' => 'Only pending reservations can be accepted.'
            ], 422);
        }

        $reservation->update([
            'status' => 'confirmed',
            'cancellation_reason' => null // Clear any previous cancellation reason
        ]);

        $reservation->load('user');

        return response()->json([
            'message' => 'Reservation accepted successfully!',
            'reservation' => $reservation
        ]);
    }

    /**
     * Decline a reservation (restaurant owner only).
     */
    public function decline(Request $request, Reservation $reservation)
    {
        $user = Auth::user();
        $restaurant = $reservation->restaurant;

        // Check if user is the owner of the restaurant
        if ($restaurant->owner_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized. Only the restaurant owner can decline reservations.'], 403);
        }

        // Check if reservation is in pending status
        if ($reservation->status !== 'pending') {
            return response()->json([
                'message' => 'Only pending reservations can be declined.'
            ], 422);
        }

        // Validate the decline reason
        $request->validate([
            'cancellation_reason' => 'required|string|min:10|max:500'
        ], [
            'cancellation_reason.required' => 'Please provide a reason for declining this reservation.',
            'cancellation_reason.min' => 'The reason must be at least 10 characters.',
            'cancellation_reason.max' => 'The reason cannot exceed 500 characters.'
        ]);

        $reservation->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason
        ]);

        $reservation->load('user');

        return response()->json([
            'message' => 'Reservation declined.',
            'reservation' => $reservation
        ]);
    }
}
