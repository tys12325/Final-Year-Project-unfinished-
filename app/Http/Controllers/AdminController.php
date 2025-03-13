<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Validation\Rule;
use App\Events\StatusUpdated;
use App\Models\Feedback;
use App\Models\uni;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

/**
 * Description of AdminController
 *
 * @author SHABI
 */
class AdminController {

    //put your code here
    public function index() {
        $applications = Application::with('universityContent')->get();
        return view('admin.applications', compact('applications'));
    }

    public function show($id) {
        $application = Application::with('universityContent')->findOrFail($id);
        return view('admin.application_details', compact('application'));
    }

    public function updateStatus(Request $request, $id) {
        $application = Application::findOrFail($id);

        $request->validate([
            'status' => [
                'required',
                Rule::in(['Verifying', 'Checking', 'Accepted', 'Rejected']),
                function ($attribute, $value, $fail) use ($application) {
                    $currentStatus = $application->status;

                    if ($value === $currentStatus) {

                        $fail('The status ' . $currentStatus . ' is already set.');
                    }
                    if ($currentStatus === 'Verifying' && !in_array($value, ['Checking', 'Rejected'])) {
                        return redirect()->back()->with('error', 'The status must be "Checking" or "Rejected" after "Verifying".');
                    }
                    if ($currentStatus === 'Checking' && !in_array($value, ['Accepted', 'Rejected'])) {

                        return redirect()->back()->with('error', 'The status must be "Accepted" or "Rejected" after "Checking".');
                    }
                    if (in_array($currentStatus, ['Accepted', 'Rejected'])) {
                        return redirect()->back()->with('error', 'The status cannot be changed once it is "Accepted" or "Rejected".".');
                    }
                },
            ],
        ]);

        $application->status = $request->status;
          broadcast(new StatusUpdated($application))->toOthers();

        $application->save();
        


        return redirect()->back()->with('success', 'Status updated successfully');
    }

    public function filter(Request $request) {
        $query = Application::query();

      
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

   
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->sort_by) {
        if ($request->sort_by == 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($request->sort_by == 'newest') {
            $query->orderBy('created_at', 'desc');
        }
    }
   
        $applications = $query->get();
        
        return view('admin.applications', compact('applications'));
        
    }
    
    public function feedbackChart()
    {
        $feedbackData = [
            'filters1' => Feedback::selectRaw('
                SUM(CASE WHEN filters1 = 1 THEN 1 ELSE 0 END) as yes,
                SUM(CASE WHEN filters1 = 0 THEN 1 ELSE 0 END) as no
            ')->first(),
            'filters2' => Feedback::selectRaw('
                SUM(CASE WHEN filters2 = 1 THEN 1 ELSE 0 END) as yes,
                SUM(CASE WHEN filters2 = 0 THEN 1 ELSE 0 END) as no
            ')->first(),
            'filters3' => Feedback::selectRaw('
                SUM(CASE WHEN filters3 = 1 THEN 1 ELSE 0 END) as yes,
                SUM(CASE WHEN filters3 = 0 THEN 1 ELSE 0 END) as no
            ')->first(),
            'filters4' => Feedback::selectRaw('
                SUM(CASE WHEN filters4 = 1 THEN 1 ELSE 0 END) as yes,
                SUM(CASE WHEN filters4 = 0 THEN 1 ELSE 0 END) as no
            ')->first(),
            'rating' => Feedback::selectRaw('
                SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as poor,
                SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as fair,
                SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as good,
                SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as very_good,
                SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as excellent
            ')->first()
        ];

        return view('admin.feedback_chart', compact('feedbackData'));
    }
    
    public function universityRanking() {
    $universities = uni::leftJoin('ratings', 'uni.uniID', '=', 'ratings.uniID')
        ->select('uni.uniID', 'uni.uniName')
        ->selectRaw('COALESCE(AVG(ratings.rating), 0) as average_rating')
        ->selectRaw('COUNT(ratings.id) as total_reviews')
        ->groupBy('uni.uniID', 'uni.uniName') // Fix the alias issue
        ->orderByDesc('average_rating')
        ->get();

    return view('admin.university_ranking', compact('universities'));
    }
    public function updateUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
        ]);

        $admin = Auth::user(); // Get the logged-in admin
        $admin->name = $request->username;
        $admin->save();

        return response()->json(['success' => true]);
    }
}
