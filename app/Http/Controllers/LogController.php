<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    // University Logs
    public function uniIndex()
    {
        return view('logs.uni'); // Render university logs view
    }

    public function fetchUniLogs()
    {
        $logFile = storage_path('logs/university_changes.log');

        if (!File::exists($logFile)) {
            return response()->json(['logs' => 'No university logs found.']);
        }

        // Read entire log file contents
        $logs = File::get($logFile);
        return response()->json(['logs' => $logs]);
    }

    public function clearUniLogs()
    {
        $logFile = storage_path('logs/university_changes.log');

        if (File::exists($logFile)) {
            File::put($logFile, ''); // Clear log file
        }

        return response()->json(['message' => 'University logs cleared successfully!']);
    }

    // Course Logs
    public function courseIndex()
    {
        return view('logs.course'); // Render course logs view
    }

    public function fetchCourseLogs()
    {
        $logFile = storage_path('logs/course_changes.log');

        if (!File::exists($logFile)) {
            return response()->json(['logs' => 'No course logs found.']);
        }

        // Read entire log file contents
        $logs = File::get($logFile);
        return response()->json(['logs' => $logs]);
    }

    public function clearCourseLogs()
    {
        $logFile = storage_path('logs/course_changes.log');

        if (File::exists($logFile)) {
            File::put($logFile, ''); // Clear log file
        }

        return response()->json(['message' => 'Course logs cleared successfully!']);
    }
}