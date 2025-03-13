<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\uni;  
use App\Models\Rating; 


class RatingController extends Controller {

    public function index()
    {
        $university = uni::paginate(10); 
        return view('FeedbackAndSupport.University_list',compact('university'));

    }
    public function store(Request $request)
    {
        $request->validate([
            'uniID' => 'required|exists:uni,uniID',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        Rating::create([
            'user_id' => Auth::id(),
            'uniID' => $request->uniID,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Rating submitted successfully!');
    }
    
    public function create($uniID)
    {
        $uni = uni::findOrFail($uniID);
        return view('FeedbackAndSupport.UniversityRating', compact('uni'));
    }

    
}