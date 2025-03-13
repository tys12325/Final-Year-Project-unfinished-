<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\uni;
use App\Models\Application;

class applicationController extends Controller {

    public function showUni() {
        $universities = uni::orderBy('name')->get();
        return view('application', compact('universities'));
    }

    public function store(Request $request) {
        
        $request->validate([
            'lastName' => 'required|string',
            'firstName' => 'required|string',
            'ic' => 'required|string|unique:applications,ic',
            'BirthDayDate' => 'required|date',
            'gender' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'education' => 'required|string',
            'nationality' => 'required|string',
            'otherNationality' => 'nullable|string',
            'university' => 'required|exists:universities,id',
            'certificate' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'fileInput' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $certificatePath = $request->hasFile('certificate') ?
        $request->file('certificate')->store('certificates', 'public') : null;

        $profilePicturePath = $request->hasFile('fileInput') ?
        $request->file('fileInput')->store('fileInput', 'public') : null;

        $application = Application::create([
                    'lastName' => $request->lastName,
                    'firstName' => $request->firstName,
                    'ic' => $request->ic,
                    'BirthDayDate' => $request->BirthDayDate,
                    'gender' => $request->gender,
                    'certificate' => $certificatePath,
                    'fileInput' => $profilePicturePath,
                    'address' => $request->address,
                    'address2' => $request->address2,
                    'address3' => $request->address3,
                    'phone' => $request->phone,
                    'education' => $request->education,
                    'nationality' => $request->nationality,
                    'otherNationality' => $request->otherNationality,
                    'university' => $request->university,
                    'status' => 'Submitted'
        ]);
        if ($application) {
            session(['application' => true]);
        }
        
         return redirect()->route('application-tracking', ['id' => $application->id])->with('success', 'Application submitted successfully!');

    }

   
}
