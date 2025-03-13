<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Uni;

class HomeController extends Controller
{
   
    public function filterResults(Request $request)
    {
        $query = Course::query()->with('university');


        if ($request->filled('uni')) {
            $query->whereHas('university', function ($q) use ($request) {
                $q->where('uniName', $request->uni);
            });
        }
        if ($request->filled('city')) {
            $query->whereHas('university', function ($q) use ($request) {
                $q->where('Address', 'LIKE', '%' . $request->city . '%');
            });
        }
        if ($request->filled('level')) {
            $query->where('studyLevel', $request->level);
        }

        // Get filtered courses first, then all courses
        $filteredCourses = $query->get();
        $allCourses = Course::with('university')->get();

        return view('results', [
            'universities' => Uni::distinct()->pluck('uniName'),
            'cities' => Uni::distinct()->pluck('Address')->map(fn($a) => $this->extractCity($a))->unique()->values(),
            'levels' => Course::distinct()->pluck('studyLevel'),
            'filteredCourses' => $filteredCourses,
            'allCourses' => $allCourses
        ]);
    }

    public function index()
    {

        $universities = Uni::distinct()->pluck('uniName'); 
        // Extract only the city from each university's address dynamically
        $cities = Uni::distinct()->pluck('Address')->map(function ($address) {
            return $this->extractCity($address); // Extract city
        })->unique()->values(); 

        // Get unique study levels
        $levels = Course::distinct()->pluck('studyLevel');

        // Get all courses
        $courses = Course::with('university')->get();

        return view('home', compact('universities', 'cities', 'levels', 'courses'));
    }

    // Function to extract city from address
    private function extractCity($address)
    {
        // Match city-like patterns in address
        if (preg_match('/^([A-Za-z\s]+)(?:,? \d{5})?/', $address, $matches)) {
            return trim($matches[1]); // Extract matched city name
        }

        // If no match, return original address (fallback)
        return $address;
    }


    public function search(Request $request)
    {
        $query = Course::with('university');

        // Search by university name if provided
        if ($request->filled('uni')) {
            $query->whereHas('university', function ($q) use ($request) {
                $q->where('uniName', 'LIKE', '%' . $request->uni . '%');
            });
        }

        // Search by course name if provided
        if ($request->filled('field_of_study')) {
            $query->where('courseName', 'LIKE', '%' . $request->field_of_study . '%');
        }

        // Search by city if provided
        if ($request->filled('city')) {
            $query->whereHas('university', function ($q) use ($request) {
                $q->where('Address', 'LIKE', '%' . $request->city . '%');
            });
        }

        // Search by study level if provided
        if ($request->filled('study_level')) {
            $query->where('studyLevel', $request->study_level);
        }

        $courses = $query->get();

        // Generate HTML dynamically
        $html = "";
        foreach ($courses as $course) {
            $html .= "<div class='result-item'>
                        <strong>{$course->courseName}</strong> ({$course->studyLevel}) - {$course->university->uniName}
                      </div>";
        }
        return $html;
    }

}
