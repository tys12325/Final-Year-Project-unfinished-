<?php

// app/Http/Controllers/Api/CourseController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Uni;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function getCourses($uniID, Request $request)
    {
        // Validate uniID
        if (!is_numeric($uniID)) {
            return response()->json(['error' => 'Invalid university ID'], 400);
        }

        // Fetch offset and limit from the request
        $offset = $request->query('offset', 0);
        $limit = $request->query('limit', 10);

        // Find the university
        $university = Uni::find($uniID);

        // If university not found, return a 404 response
        if (!$university) {
            return response()->json(['error' => 'University not found'], 404);
        }

        // Fetch courses for the university with pagination
        $courses = $university->courses()
            ->offset($offset)
            ->limit($limit)
            ->get();

        // Return the courses as JSON
        return response()->json($courses);
    }
}