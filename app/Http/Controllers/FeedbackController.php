<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller {

    public function store(Request $request) {
        
//        $validated = $request->validate([
//            'filters1' => 'required|integer',
//            'filters2' => 'required|integer',
//            'filters3' => 'required|integer',
//            'filters4' => 'required|integer',
//            'rating' => 'required|integer|min:1|max:5',
//            'comment' => 'nullable|string|max:500'
//        ]);
//
//        Feedback::create($validated);
//        session()->flash('success', 'Feedback submitted successfully!');
//        return redirect()->route('feedback');
        $validated = $request;
        Feedback::create([
            'user_id'  => Auth::id(), 
            'filters1' => $validated['filters1'],
            'filters2' => $validated['filters2'],
            'filters3' => $validated['filters3'],
            'filters4' => $validated['filters4'],
            'rating'   => $validated['rating'],
            'comment'  => $validated['comment'],
        ]);

        return redirect()->back()->with('success', 'Feedback submitted successfully!');
    }
}
