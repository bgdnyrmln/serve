<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Session;

class SessionController extends Controller
{
    public function index()
    {
        return response()->json(Session::all());
    }


    public function destroy($user)
    {
        $session = Session::where('user_id', $user)->delete();
        if (!$session) {
            return response()->json(['message' => 'Session not found'], 404);
        }
        return response()->json(['message' => 'Session deleted successfully']);
    }

    public function show($id)
    {
        $session = Session::find($id);

        if (!$session) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        return response()->json($session);
    }


}

