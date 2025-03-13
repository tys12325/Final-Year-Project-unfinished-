<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use app\Models\User;

class ProfileController extends Controller
{
    
    public function edit(){
        $user = Auth::user();
        return view('user.profileEdit', compact('user'));
    }
    

public function update(Request $request) {
    $user = Auth::user();

    $request->validate([
        'name' => 'nullable|string',
        'ic' => 'nullable|string',
        'BirthDayDate' => 'nullable|date',
        'gender' => 'nullable|string',
        'address' => 'nullable|string',
        'address2' => 'nullable|string',
        'address3' => 'nullable|string',
        'education' => 'nullable|string',
        'nationality' => 'nullable|string',
        'otherNationality' => 'nullable|string',
        'certificate' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        'fileInput' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
    ]);

    // Handle file uploads
    $certificatePath = $request->hasFile('certificate') 
    ? $request->file('certificate')->store('certificates', 'public') 
    : $user->certificate;


    if ($request->hasFile('fileInput')) {
        if ($user->fileInput) {
            Storage::delete('public/' . $user->fileInput);
        }
        $profilePicturePath = $request->file('fileInput')->store('fileInput', 'public');
    } else {
        $profilePicturePath = $user->fileInput;
    }

    // Ensure NULL values get updated
    $updated = $user->update(array_filter([
        'name' => $request->name,
        'ic' => $request->ic,
        'BirthDayDate' => $request->BirthDayDate,
        'gender' => $request->gender,
        'certificate' => $certificatePath,
        'fileInput' => $profilePicturePath,
        'address' => $request->address,
        'address2' => $request->address2,
        'address3' => $request->address3,
        'education' => $request->education,
        'nationality' => $request->nationality,
        'otherNationality' => $request->otherNationality,
    ], fn($value) => $value !== null)); 

    return redirect()->route('profile.edit')
        ->with($updated ? 'success' : 'error', $updated ? 'User Details updated successfully!' : 'User Details update failed!');
}


}
