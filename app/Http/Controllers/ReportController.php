<?php

namespace App\Http\Controllers;

use App\Models\Uni;
use App\Models\Course;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all universities with their courses
        $universities = Uni::with('courses')->get();

        // Calculate totals
        $totalUniversities = Uni::count();
        $totalCourses = Course::count();

        $universities = $universities->map(function ($university) {
            $extractedCity = $this->extractCity($university->Address); 
            $university->city = $extractedCity;
            return $university;
        });

        // Get all unique cities from universities (Instead of plucking Address separately)
        $cities = $universities->pluck('city')->unique()->values();

        // Get the last updated timestamp
        $lastUpdated = now()->format('F j, Y, H:i');

        // Pass data to the view
        return view('report', compact('universities', 'cities', 'totalUniversities', 'totalCourses', 'lastUpdated'));
    }

private function extractCity($address)
{
    if (empty($address)) {
        return 'No Address Provided';
    }

    // Extract city between the last comma and postal code
    if (preg_match('/,\s*([^,]+?),\s*\d{5}/', $address, $matches)) {
        return trim($matches[1]); // Extract city name
    }

    return 'No Match Found';
}

}
