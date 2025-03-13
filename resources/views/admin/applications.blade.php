@extends('layout.adminInterface')
@section('title', 'Application List')

@section('head')
@vite(['resources/css/admin/ApplicationDashboard.css'])
@endsection

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

@section('content')
<a href="{{ route('admin.applications') }}" id="backButton"><i class="fas fa-chevron-left fa-2x"></i></a>
<div class="container">
    <h2 class="admin-title">Admin Panel - Manage Applications</h2>

   <div class='filter-form-container'>
    <form method="GET" action="{{ route('admin.filter') }}" class="filter-form">
        <div class="filter-row">
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="Submitted" {{ request('status') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="Verifying" {{ request('status') == 'Verifying' ? 'selected' : '' }}>Verifying</option>
                    <option value="Checking" {{ request('status') == 'Checking' ? 'selected' : '' }}>Checking</option>
                    <option value="Accepted" {{ request('status') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>

            <div class="form-group">
                <label for="sort_by">Sort By</label>
                <select name="sort_by" id="sort_by" class="form-control">
                    <option value="">Default</option>
                    <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>oldest</option>
                    <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Newest</option>
                </select>
            </div>

            <div class="BtnContainer">
                <button type="submit" class="filterBtn">Filter</button>
                <a href="{{ route('admin.applications') }}" class="ResetBtn">Reset</a>
            </div>
        </div>
    </form>
</div>


    

    <div class="table-responsive">
        <table class="table table-bordered admin-table">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Duration</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($applications as $application)
                <tr>
                    <td>{{ $application->id }}</td>
                    <td>
                        <a href="{{ route('admin.applications.show', $application->id) }}" class="applicant-link">
                            {{ substr($application->lastName, 0, 7) }} {{ substr($application->firstName, 0, 8) }}
                        </a>
                    </td>
                    <td>
                        <span class="status-badge {{ strtolower($application->status) }}">
                            {{ $application->status }}
                        </span>
                    </td>
                    <td>{{ $application->created_at }}</td>
                    <td>{{ $application->created_at->diffForHumans() }}</td>
                    <td>
                        <form method="POST" action="{{ url('/admin/applications/' . $application->id . '/update-status') }}" class="status-form">
                            @csrf
                            <div class="input-group">
                                <select name="status" class="form-control status-dropdown">
                                    <option value="Verifying" {{ $application->status == 'Verifying' ? 'selected' : '' }} {{ in_array($application->status, ['Checking', 'Accepted', 'Rejected']) ? 'disabled' : '' }}>Verifying</option>
                                    <option value="Checking" {{ $application->status == 'Checking' ? 'selected' : '' }} {{ in_array($application->status, ['Accepted', 'Rejected', 'Submitted']) ? 'disabled' : '' }}>Checking</option>
                                    <option value="Accepted" {{ $application->status == 'Accepted' ? 'selected' : '' }} {{ !in_array($application->status, ['Checking', 'Accepted']) ? 'disabled' : '' }}>Accepted</option>
                                    <option value="Rejected" {{ $application->status == 'Rejected' ? 'selected' : '' }} {{ in_array($application->status, ['Accepted', 'Submitted']) ? 'disabled' : '' }}>Rejected</option>
                                </select>
                            </div>
                    </td>
                    <td>
                        <div class="input-group-append">
                            <button  wire:click="updateStatus" type="submit" class="btn btn-primary" {{ in_array($application->status, ['Accepted', 'Rejected']) ? 'disabled' : '' }}>Update</button>
                        </div>
                    </td>
                    </form>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    setTimeout(function () {
        const successMessage = document.querySelector('.alert-success');
        const errorMessage = document.querySelector('.alert-danger');

        if (successMessage) {
            successMessage.classList.add('fade-out');
        }
        if (errorMessage) {
            errorMessage.classList.add('fade-out');
        }
    }, 4000);
</script>
@endsection