<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Uni;
use App\Models\Course;

class UniversityController extends Controller
{
    public function index()
    {
        
        $universities = Uni::all();
        return view('universities.index', compact('universities'));
    }
    
    public function showCourses($uniID)
    {
        $university = Uni::with('courses')->where('uniID', $uniID)->firstOrFail();
        return view('universities.courses', compact('university'));
    }

public function update(Request $request, $id)
{
    $university = Uni::find($id);

    if (!$university) {
        Log::channel('university_log')->warning("University not found", ['uniID' => $id]);
        return response()->json(['success' => false, 'message' => 'University not found.'], 404);
    }

    // Validate input
    $request->validate([
        'image' => 'required|string',
        'uniName' => 'required|string|max:255'
    ]);

    // Store the original data before updating
    $originalData = $university->getOriginal();

    // Update fields
    $university->image = $request->image;
    $university->uniName = $request->uniName;
    $university->Address = $request->Address;
    $university->ContactNumber = $request->ContactNumber;
    $university->OperationHour = $request->OperationHour;
    $university->DateOfOpenSchool = $request->DateOfOpenSchool;
    $university->Category = $request->Category;
    $university->Description = $request->Description;
    $university->Founder = $request->Founder;
    $university->EstablishDate = $request->EstablishDate;
    $university->Ranking = $request->Ranking;
    $university->save(); // Save the changes

    if ($university->save()) {
        // Get changed fields
        $updatedData = $university->getChanges();

        // Capture only the previous values of the changed fields
        $beforeChanges = array_intersect_key($originalData, $updatedData);

        // Log before changes
        Log::channel('university_log')->info("University Update - Before Changes", [
            'uniID' => $id,
            'before' => $beforeChanges
        ]);

        // Log after changes
        Log::channel('university_log')->info("University Update - After Changes", [
            'uniID' => $id,
            'after' => $updatedData
        ]);

        return response()->json(['success' => true, 'message' => 'University updated successfully.']);
    } else {
        Log::channel('university_log')->error("Failed to update university", ['uniID' => $id]);
        return response()->json(['success' => false, 'message' => 'Update failed.']);
    }
}


    
    public function updateCourse(Request $request, $uniID, $courseID)
    {
        Log::info("Received update request for Programme ID: $courseID in University ID: $uniID", $request->all()); // Debugging

        $course = Course::where('uniID', $uniID)->where('courseID', $courseID)->first();

        if (!$course) {
            return response()->json(['success' => false, 'message' => 'Course not found.'], 404);
        }

        $request->validate([
            'image' => 'required|string',
            'uniName' => 'required|string|max:255',
            'Address' => 'required|string|max:255',
            'ContactNumber' => 'required|string|max:20',
            'OperationHour' => 'required|string|max:50',
            'DateOfOpenSchool' => 'required|string',
            'Category' => 'required|string|max:100',
            'Description' => 'nullable|string',
            'Founder' => 'nullable|string|max:255',
            'EstablishDate' => 'required|string',
            'Ranking' => 'required|integer|min:1',
            'NumOfCourses' => 'required|integer',
        ]);

        $course->courseName = $request->courseName;
        $course->credits = $request->credits;

        if ($course->save()) {
            Log::info("Programme updated successfully", ['courseID' => $courseID]); // Debugging
            return response()->json(['success' => true, 'message' => 'Programme updated successfully.']);
        } else {
            Log::error("Failed to update Programme", ['courseID' => $courseID]); // Debugging
            return response()->json(['success' => false, 'message' => 'Update failed.']);
        }
    }
public function create()
{
    // Fetch distinct categories from the universities table
    $categories = Uni::distinct()->pluck('Category'); 
    return view('universities.create', compact('categories')); // Pass the categories to the view
}
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'uniName' => 'required|string',
            'Address' => 'required|string',
            'ContactNumber' => 'required|string',
            'OperationHour' => 'required|string',
            'DateOfOpenSchool' => 'required|date',
            'Category' => 'required|string',
            'Description' => 'required|string',
            'Founder' => 'required|string',
            'EstablishDate' => 'required|date',
            'Ranking' => 'nullable|integer',
            'NumOfCourses' => 'nullable|integer',
            'image' => 'required|string',

        ]);
        $uniName = strtolower(trim($request->uniName));
        $exists = Uni::whereRaw('LOWER(TRIM(uniName)) = ?', [$uniName])->exists();
        if ($exists) {
            return redirect()->back()->with('duplicateError', "Duplicate University: <strong>{$request->uniName}</strong> already exists.");
        }
        // Convert dates to dd/mm/yyyy format
        $dateOfOpenSchool = date("d/m/Y", strtotime($request->DateOfOpenSchool));
        $establishDate = date("d/m/Y", strtotime($request->EstablishDate));

        // Save data
        $university = new Uni();
        $university->uniName = $request->uniName;
        $university->Address = $request->Address;
        $university->ContactNumber = $request->ContactNumber;
        $university->OperationHour = $request->OperationHour;
        $university->DateOfOpenSchool = $dateOfOpenSchool;
        $university->Category = $request->Category;
        $university->Description = $request->Description;
        $university->Founder = $request->Founder;
        $university->EstablishDate = $establishDate;
        $university->Ranking = $request->Ranking;
        $university->NumOfCourses = $request->NumOfCourses;
        $university->image = $request->image;



        $university->save();
        if ($university->save()) {
            Log::channel('university_log')->info("University Created", ['uniID' => $university->uniID, 'uniName' => $university->uniName]);
        }
        return redirect()->route('universities.index')->with('success', 'University added successfully.');
    }


    
    public function destroy($id)
    {
        $university = Uni::find($id);

        if (!$university) {
            Log::channel('university_log')->warning("University not found", ['uniID' => $id]);
            return response()->json(['success' => false, 'message' => 'Record not found']);
        }

        $university->delete();
        Log::channel('university_log')->info("University deleted successfully", ['uniID' => $university->uniID, 'uniName' => $university->uniName, 'Num Of Courses' => $university->NumOfCourses]);

        return response()->json(['success' => true]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query'); // Get the search query

        // Search for universities where uniName matches the query
        $universities = Uni::where('uniName', 'like', "%{$query}%")
                            ->get();

        return response()->json($universities);
    }


}



