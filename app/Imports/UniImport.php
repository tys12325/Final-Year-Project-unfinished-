<?php

namespace App\Imports;

use App\Models\Uni;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
use Illuminate\Support\Facades\Log;

class UniImport implements ToModel, WithHeadingRow
{
    public $logMessages = [];
    protected $selectedRows;
    public $successfulCount = 0; // Track successful insertions
    public function __construct(array $selectedRows = [])
    {
        $this->selectedRows = $selectedRows; // Store selected row indices
        DB::statement('ALTER TABLE uni AUTO_INCREMENT = 1');
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

        $uniID = isset($row['uni_id']) ? $row['uni_id'] : 1;

        if (!isset($row['uni_name'], $row['address'], $row['contact_number'], $row['category'])) {
            $errorMessage = "<span style='color: red;'>X  <b>Error:</b> Incorrect file format for University data. Please upload the correct file.</span></br>";

            // Check if the message is already logged
            if (!in_array($errorMessage, $this->logMessages)) {
                $this->logMessages[] = $errorMessage;
            }
            return null;
        }

        $exists = Uni::where('uniName', $row['uni_name'])->exists();

        if ($exists) {
            $this->logMessages[] = "<span style='color: red;'>X </span>Duplicate University: <span style='color: red;'>{$row['uni_name']}</span> already exists.</br>";
            return null;
        }

        $university = Uni::create([
            'uniID'         => $uniID,
            'uniName'       => $row['uni_name'],
            'Address'       => $row['address'],
            'ContactNumber' => $row['contact_number'],
            'OperationHour' => $row['operation_hour'] ?? null,
            'DateOfOpenSchool' => $row['date_of_open_school'] ?? null,
            'Category'      => $row['category'],
            'Description'   => $row['description'] ?? null,
            'Founder'       => $row['founder'] ?? null,
            'EstablishDate' => $row['establish_date'] ?? null,
            'Ranking'       => $row['ranking'] ?? null,
            'NumOfCourses'  => $row['num_of_courses'] ?? null,
            'image'         => $row['image'] ?? null,
        ]);
        if ($university) {
            $this->successfulCount++; // Increment only if insertion is successful
        }
        Log::channel('university_log')->info("New University Added: [uniID: {$university->uniID}] {$row['uni_name']}");

        return $university;
    }
}