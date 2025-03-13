@extends('layout.adminInterface')

@section('title', 'Application Details')
    
@section('head')
   @vite(['resources/css/admin/applicationManage.css'])
@endsection

@section('content')

<a href="{{ route('admin.applications') }}" id="backButton"><i class="fas fa-chevron-left fa-2x"></i></a>
<div class="container">
    <div class="card application-card">
       
        <div class="profile-container text-center">
            <img src="{{ asset('storage/' . $application->fileInput) }}" class="profile-picture"
                onerror="this.src='{{ asset('images/profileImage.jpeg') }}'">
            <h3 class="applicant-name">{{ $application->lastName }} {{ $application->firstName }} </h3>
        </div>

    
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th>IC:</th>
                    <td>{{ $application->ic }}</td>
                </tr>
                <tr>
                    <th>Birthday:</th>
                    <td>{{ $application->BirthDayDate }}</td>
                </tr>
                <tr>
                    <th>Gender:</th>
                    <td>{{ ucfirst($application->gender) }}</td>
                </tr>
                <tr>
                    <th>Phone:</th>
                    <td>{{ $application->phone }}</td>
                </tr>
                <tr>
                    <th>Education:</th>
                    <td>{{ ucfirst($application->education) }}</td>
                </tr>
                <tr>
                    <th>Nationality:</th>
                    <td>{{ $application->nationality == 'other' ? $application->otherNationality : ucfirst($application->nationality) }}</td>
                </tr>
                <tr>
                    <th>University:</th>
                    <td>{{ $application->universityContent ? $application->universityContent->name : 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Address:</th>
                    <td>{{ $application->address }}, {{ $application->address2 }}, {{ $application->address3 }}</td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td>
                        <span class="status-badge {{ strtolower($application->status) }}">
                            {{ $application->status }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Certificate:</th>
                    <td>
                        <a href="{{ asset('storage/' . $application->certificate) }}" target="_blank" class="btn-primary btn-sm">View Certificate</a>
                    </td>
                </tr>
            </table>
        </div>

        
    </div>
</div>
@endsection
