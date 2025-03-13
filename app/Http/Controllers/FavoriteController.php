<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use App\Models\course;

class FavoriteController extends Controller {

    public function saveFavorite(Request $request) {
        if (!Auth::check()) {
            return response()->json([
                        'error' => 'Please login to save favorites',
                        'login_url' => route('login')
                            ], 401);
        }

        $userID = Auth::id();
        $courseID = $request->courseID;

        $favorite = Favorite::where('user_id', $userID)->where('courseID', $courseID)->first();

        if ($favorite) {

            $favorite->delete();
            return response()->json(['message' => 'Removed from favorites', 'status' => 'removed']);
        } else {

            Favorite::create([
                'user_id' => $userID,
                'courseID' => $courseID
            ]);
            return response()->json(['message' => 'Added to favorites', 'status' => 'added']);
        }
    }

    public function index() {
        $user = Auth::user();
        $favorites = $user->favorites()
                ->with(['course.university'])
                ->paginate(10);

        return view('user.userFavorite', compact('favorites'));
    }

    public function destroy($courseID) {
        $user = Auth::user();
        $favorite = Favorite::where('user_id', $user->id)->where('courseID', $courseID)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Favorite not found'], 404);
    }
}
