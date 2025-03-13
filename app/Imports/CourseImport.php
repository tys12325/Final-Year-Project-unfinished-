<?php

namespace App\Imports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class CourseImport implements ToModel, WithHeadingRow
{
    public $logMessages = [];
    protected $selectedRows;
    public $successfulCount = 0; // Track successful insertions
    public function __construct(array $selectedRows = [])
    {
        $this->selectedRows = $selectedRows; // Store selected row indices
    }

    public function model(array $row)
    {
        static $rowIndex = 0; // Track the current row index

        // Skip rows that are not selected
        if (!empty($this->selectedRows)) {
            if (!in_array($rowIndex, $this->selectedRows)) {
                $rowIndex++; // Increment the row index
                return null; // Skip this row
            }
        }

        $rowIndex++; // Increment the row index

        // ✅ Check if required Course fields exist
        if (!isset($row['course_name'], $row['duration'], $row['fees_local'], $row['study_type'], $row['uniid'])) {
            $errorMessage = "<span style='color: red;'>X  <b>Error:</b> Incorrect file format for Programme data. Please upload the correct file.</span></br>";
            // Check if the message is already logged
            if (!in_array($errorMessage, $this->logMessages)) {
                $this->logMessages[] = $errorMessage;
            }
            return null;
        }

        // ✅ Check for duplicate courses based on course name AND university ID
        $exists = Course::where('courseName', $row['course_name'])
                        ->where('uniID', $row['uniid'])
                        ->exists();

        if ($exists) {
            $this->logMessages[] = "<span style='color: red;'>X </span> Duplicate Programme: <span style='color: red;'>{$row['course_name']}</span> already exists in University ID <span style='color: red;'>{$row['uniid']}</span>.</br>";
            return null;
        }

        $course = Course::create([
            'courseName'       => $row['course_name'],
            'duration'         => $row['duration'],
            'feesLocal'        => $row['fees_local'],
            'feesInternational'=> $row['fees_international'],
            'studyType'        => $row['study_type'],
            'studyLevel'       => $row['study_level'],
            'uniID'            => $row['uniid'],
        ]);
        if ($course) {
            $this->successfulCount++; // Increment only if insertion is successful
        }
        // ✅ Retrieve the correct courseID after insertion
        $courseID = $course->courseID;

        // ✅ Log with the correct courseID
        Log::channel('course_changes')->info("New Programme Added: [uniID: {$row['uniid']}, courseID: {$courseID}] {$row['course_name']}");

        return $course;
    }
}