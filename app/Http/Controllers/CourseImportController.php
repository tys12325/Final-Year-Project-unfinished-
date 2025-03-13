<?php
namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Uni;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CourseImport;
use App\Imports\UniImport;

class CourseImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
            'type' => 'required|in:course,uni' // Ensure user selects correct type
        ]);

        // Get selected rows (IDs) from the request
         $selectedRows = json_decode($request->input('selectedRows'), true);    

        // Initialize the correct import class
        $import = $request->type === 'course' ? new CourseImport($selectedRows) : new UniImport($selectedRows);

        // Perform the import
        Excel::import($import, $request->file('file'));

        // Read the file to count total rows
        $data = Excel::toArray($import, $request->file('file'));
        $rowCount = count($data[0]);
        if (empty($selectedRows)) {
            $skippedCount =0;
        }else{
            $skippedCount = $rowCount - count($selectedRows);
        }
        

        $logMessages = "";
        foreach ($import->logMessages as $message) {
            if (str_contains($message, 'Duplicate')) {
                $logMessages .= "<span style='color: red;'>$message</span>";
            } else {
                $logMessages .= "<br><span style='color: green;'>$message</span><br>";
            }
        }

        if ($skippedCount > 0) {
            $logMessages .= "<span style='color: orange;'>Skipped $skippedCount rows.</span>";
        }

        $successfulCount = $import->successfulCount; // Use actual inserted count 
        if ($successfulCount > 0 ) {
            $logMessages .= "<span style='color: green;'>Successfully added $successfulCount rows.</span><br>";
        }else{
            $logMessages .= "<span style='color: green;'>$successfulCount rows of data added to the system.</span><br>";
        }

        return back()->with('logMessage', $logMessages);
    }
}
