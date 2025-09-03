<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\password;

class Usercontroller extends Controller
{

    public function index()
{
    return response()->json(User::all());
}


    public function store()
    {
        $ValidatedAttributes = request()->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $user = User::create($ValidatedAttributes);

        Auth::login($user);

        return redirect('/cars');
    }


    public function show($user)
    {
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response(User::find($user));
    }



    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        try {
            $user->delete();
            return response()->json(['message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting user',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|in:1,2',
            'exercises_count' => 'nullable|integer|min:0',
        ]);

        $user->update($validatedData);

        return response()->json(['message' => 'User updated successfully']);
    }
}
