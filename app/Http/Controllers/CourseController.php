<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Uni;
use Illuminate\Support\Facades\Log;



class CourseController extends Controller
{
    public function filter(Request $request)
    {
        // Get filter inputs
        $search = $request->input('search');
        $selectedField = $request->input('field');
        $selectedCity = $request->input('city');
        $selectedLevel = $request->input('level');

        // Fetch unique fields of study and levels
        $fields = Course::distinct()->pluck('courseName');
        $levels = Course::distinct()->pluck('studyLevel');

        // Extract only the city from university addresses dynamically
        $cities = Uni::distinct()->pluck('Address')->map(function ($address) {
            return $this->extractCity($address); // Extract city from address
        })->unique()->values();

        // Fetch all universities
        $allUniversities = Uni::select('uniID', 'uniName', 'Category', 'Ranking', 'image')->get();

        // Fetch universities that match the selected city
        $filteredUniversities = Uni::select('uniID', 'uniName', 'Category', 'Ranking', 'image')
            ->when($selectedCity, function ($query) use ($selectedCity) {
                $query->where('Address', 'LIKE', "%$selectedCity%");
            })
            ->when($search, function ($query) use ($search) {
                $query->where('uniName', 'LIKE', "%$search%");
            })
            ->get();

        // Filter courses based on selected inputs and search
        $query = Course::with('university');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('courseName', 'LIKE', "%$search%")
                  ->orWhereHas('university', function ($uniQuery) use ($search) {
                      $uniQuery->where('uniName', 'LIKE', "%$search%");
                  });
            });
        }

        if ($selectedField) {
            $query->where('courseName', 'LIKE', "%$selectedField%");
        }
        if ($selectedCity) {
            $query->whereHas('university', function ($q) use ($selectedCity) {
                $q->where('Address', 'LIKE', "%$selectedCity%");
            });
        }
        if ($selectedLevel) {
            $query->where('studyLevel', 'LIKE', "%$selectedLevel%");
        }

        // Get filtered courses
        $filteredCourses = $query->get();

        // Fetch all courses
        $allCourses = Course::with('university')->get();
        $universities = Uni::distinct()->pluck('uniName');

            // Get filtered courses (paginate)
        $filteredCourses = $query->paginate(6); // 6 courses per page

        // Fetch all courses (paginate)
        $allCourses = Course::with('university')->inRandomOrder()->paginate(9);
    // 9 courses per page
        // Return the view with all universities + filtered data
        return view('results', compact(
            'fields', 'cities', 'levels',
            'allUniversities', 'filteredUniversities',
            'filteredCourses', 'allCourses',
            'selectedField', 'selectedCity', 'selectedLevel', 'search', 'universities'
        ));
    }

    private function extractCity($address)
    {
        // Match city-like patterns in address
        if (preg_match('/^([A-Za-z\s]+)(?:,? \d{5})?/', $address, $matches)) {
            return trim($matches[1]); // Extract matched city name
        }

        // If no match, return original address (fallback)
        return $address;
    }

    // Show all courses
    public function index(Request $request)
    {
        // Get the selected per-page value, defaulting to 10 if not set
        $perPage = $request->input('per_page', 10);

        // Ensure per-page is one of the allowed values
        $allowedPerPage = [10, 20, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        // Fetch paginated courses with university data
        $courses = Course::with('university')->paginate($perPage)->withQueryString();

        // Fetch all universities
        $universities = Uni::all();

        return view('courses.index', compact('courses', 'universities', 'perPage'));
    }


    // Show courses for a specific university
    public function showByUniversity($uniID, Request $request)
    {
        $university = Uni::findOrFail($uniID);

        // Get the selected per-page value, defaulting to 10 if not set
        $perPage = $request->input('per_page', 10);

        // Ensure per-page is one of the allowed values
        $allowedPerPage = [10, 20, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        // Fetch paginated courses for the specific university
        $courses = $university->courses()->paginate($perPage)->withQueryString();


        // Fetch all universities
        $universities = Uni::all();

        return view('courses.index', compact('courses', 'university', 'universities', 'perPage'));
    }

    
public function update(Request $request, $courseID)
{
    $course = Course::find($courseID);
    
    if (!$course) {
        Log::channel('course_changes')->warning("Programme not found", ['courseID' => $courseID]);
        return response()->json(['success' => false, 'message' => 'Programme not found'], 404);
    }

    // Validate input
    $request->validate([
        'courseName' => 'required|string|max:255',
        'duration' => 'required|string|max:255',
        'feesLocal' => 'required|string|max:255', // Changed from numeric to string
        'feesInternational' => 'required|string|max:255', // Changed from numeric to string
        'studyType' => 'required|string|max:255',
        'studyLevel' => 'required|string|max:255'
    ]);

    $originalData = $course->getOriginal(); // Full original data
    $course->courseName = $request->courseName;
    $course->duration = $request->duration;
    $course->feesLocal = $request->feesLocal;
    $course->feesInternational = $request->feesInternational;
    $course->studyType = $request->studyType;
    $course->studyLevel = $request->studyLevel;
    $course->save(); // Save the changes

    if ($course->save()) {
        $updatedData = $course->getChanges(); // Only changed fields

        // Capture only the previous values of the changed fields
        $beforeChanges = array_intersect_key($originalData, $updatedData);

        // Log before changes
        Log::channel('course_changes')->info("Programme Update - Before Changes", [
            'courseID' => $courseID,
            'before' => $beforeChanges
        ]);

        // Log after changes
        Log::channel('course_changes')->info("Programme Update - After Changes", [
            'courseID' => $courseID,
            'after' => $updatedData
        ]);

        return response()->json(['success' => true, 'message' => 'Programme updated successfully.']);
    }
}

  
    public function create()
    {
        // Fetch all universities, study types, and study levels from the database
        $universities = Uni::all(); // Fetch all universities
        $studyTypes = Course::distinct()->pluck('studyType'); 

        $studyLevels = Course::distinct()->pluck('studyLevel');
         return view('courses.create', compact('universities', 'studyTypes', 'studyLevels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'courseName' => 'required|string|max:255',
            'duration' => 'required|string|max:100',
            'feesLocal' => 'required|numeric',
            'feesInternational' => 'required|numeric',
            'studyType' => 'required|string|max:100',
            'studyLevel' => 'required|string|max:100',
            'uniName' => 'required|string|exists:uni,uniName'
        ]);

        // Ensure the CourseName matches the chosen Study Level
        $validStudyLevels = [
            'Foundation' => 'Foundation in',
            'Diploma' => 'Diploma in',
            'Bachelor' => 'Bachelor of',
            'Master' => 'Master in',
            'PhD' => 'PhD in'
        ];

        if (isset($validStudyLevels[$request->studyLevel])) {
            $expectedPrefix = $validStudyLevels[$request->studyLevel];

            if (!str_starts_with($request->courseName, $expectedPrefix)) {
                return redirect()->back()->with('duplicateError', 
                    "Invalid Course Name: <strong>{$request->courseName}</strong> does not match the selected Study Level: <strong>{$request->studyLevel}</strong>."
                );
            }
        }

        $request->merge([
            'feesLocal' => 'RM ' . $request->feesLocal,
            'feesInternational' => 'RM ' . $request->feesInternational,
            'duration' => $request->duration . ' Years', // Append "Years" before storing
        ]); 

        $university = Uni::where('uniName', $request->uniName)->first();

        if (!$university) {
            return back()->withErrors(['uniName' => 'Invalid university selected.']);
        }

        $courseName = strtolower(trim($request->courseName));
        $exists = Course::whereRaw('LOWER(TRIM(courseName)) = ?', [$courseName])
                        ->where('uniID', $university->uniID) // Ensure course is checked under the same university
                        ->exists();

        if ($exists) {
            return redirect()->back()->with('duplicateError', 
                "Duplicate Course: <strong>{$request->courseName}</strong> already exists under <strong>University: {$university->uniName} (ID: {$university->uniID})</strong>."
            );
        }
        $course = Course::create([
            'courseName' => $request->courseName,
            'duration' => $request->duration,
            'feesLocal' => $request->feesLocal,
            'feesInternational' => $request->feesInternational,
            'studyType' => $request->studyType,
            'studyLevel' => $request->studyLevel,
            'uniID' => $university->uniID,
        ]);

        $courseData = [
            'courseID' => $course->courseID, // Ensure courseID appears first
            'courseName' => $course->courseName,
            'duration' => $course->duration,
            'feesLocal' => $course->feesLocal,
            'feesInternational' => $course->feesInternational,
            'studyType' => $course->studyType,
            'studyLevel' => $course->studyLevel,
            'uniID' => $course->uniID,
        ];

        Log::channel('course_changes')->info("New Programme Created", $courseData);



        return redirect()->route('courses.index')->with('success', 'Programme added successfully!');
    }

 
        

    
    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            Log::channel('course_changes')->warning("Attempted to delete a non-existent Programme", ['courseID' => $id]);
            return response()->json(['success' => false, 'message' => 'Programme not found'], 404);
        }

        // Extract only the required fields
        $courseData = $course->only(['courseID', 'courseName', 'duration', 'feesLocal', 'feesInternational', 'studyType', 'studyLevel', 'uniID']);

        Log::channel('course_changes')->info("Programme Deleted", $courseData);

        $course->delete();
        return response()->json(['success' => true]);
    }
    public function search(Request $request)
    {
        $query = $request->input('query'); // Get the search query

        // Search for courses where courseName matches the query
        $courses = Course::where('courseName', 'like', "%{$query}%")
                         ->with('university') // Eager load the university relationship
                         ->get();

        return response()->json($courses);
    }
}
