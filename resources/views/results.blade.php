@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Title & Search Bar -->
    <form action="{{ route('filter.results') }}" method="GET" class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mb-0">Recommended Courses</h1>

            <!-- Search Bar & Button -->
            <div class="d-flex col-md-6">
                <input type="text" name="search" id="search" class="form-control me-2"
                    placeholder="Enter course name..." value="{{ request('search') }}" style="height: 38px;">
                <button type="submit" class="btn btn-primary" style="height: 38px;">Find Courses</button>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mt-3">

            <div class="col-md-4 mb-3">
                <label for="uni" class="fw-semibold">University</label>
                <select name="uni" id="uni" class="form-select shadow-sm">
                    <option value="">Select University</option>
                    @foreach($universities as $university)
                        <option value="{{ $university }}" {{ request('uni') == $university ? 'selected' : '' }}>
                            {{ $university }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="city">City</label>
                <select name="city" id="city" class="form-control">
                    <option value="">Select City</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="level">Study Level</label>
                <select name="level" id="level" class="form-control">
                    <option value="">Select Level</option>
                    @foreach($levels as $level)
                        <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>
                            {{ $level }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <button type="submit" class="btn btn-secondary">Apply Filters</button>
        </div>
    </form>

    <div class="mt-4">
        <h3>Showing Results:</h3>

        @if($filteredUniversities->isEmpty() && $filteredCourses->isEmpty())
            <p class="text-center mt-4">No results found. Try different filters or search terms.</p>
        @endif

        <!-- Filtered Universities -->
        @if(!$filteredUniversities->isEmpty())
            <h3>Filtered Universities</h3>
            <div class="row">
                @foreach($filteredUniversities as $uni)
                    <div class="col-md-12 mb-3">
                        <div class="card d-flex flex-row align-items-center p-3 shadow-sm">
                            <img src="{{ asset('storage/' . $uni->image) }}" alt="University Image"
                                class="img-fluid rounded" style="width: 120px; height: 80px; object-fit: cover;">
                            <div class="ms-3">
                                <h5>{{ $uni->uniName }}</h5>
                                <p><strong>Category:</strong> {{ $uni->Category }}</p>
                                <p><strong>Ranking:</strong> {{ $uni->Ranking }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Filtered Courses -->
        @if(!$filteredCourses->isEmpty())
            <h3 class="mt-5">Filtered Courses</h3>
            <div class="row">
                @foreach($filteredCourses as $course)
                    <div class="col-md-12 mb-3">
                        <div class="card d-flex flex-row align-items-center p-3 shadow-sm">
                            <img src="{{ asset('storage/' . $course->university->image) }}" alt="Course Image"
                                class="img-fluid rounded" style="width: 120px; height: 80px; object-fit: cover;">
                            <div class="ms-3">
                                <h5>{{ $course->courseName }}</h5>
                                <p><strong>University:</strong> {{ $course->university->uniName }}</p>
                                <p><strong>CourseName: </strong> {{ $course->courseName }}</p>
                                <p><strong>Duration: </strong> {{ $course->duration }}</p>
                                <p><strong>Study Type:</strong> {{ $course->studyType }}</p>
                                <p><strong>Fees Local:</strong> {{ $course->feesLocal }}</p>
                                <p><strong>Fees International:</strong> {{ $course->feesInternational }}</p>
                                <p><strong>Study Level:</strong> {{ $course->studyLevel }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Non-Duplicate Courses -->
        @php
            $uniqueCourses = $allCourses->diff($filteredCourses);
        @endphp

        @if(!$uniqueCourses->isEmpty())
            <h4 class="mt-5">Other Courses</h4>
            <div class="row">
                @foreach($uniqueCourses as $course)
                    <div class="col-md-12 mb-3">
                        <div class="card d-flex flex-row align-items-center p-3 shadow-sm">
                            <img src="{{ asset('storage/' . $course->university->image) }}" alt="Course Image"
                                class="img-fluid rounded" style="width: 120px; height: 80px; object-fit: cover;">
                            
                            <div class="ms-3">
                                <h5>{{ $course->courseName }}</h5>
                                <p><strong>University:</strong> {{ $course->university->uniName }}</p>
                                <p><strong>CourseName: </strong> {{ $course->courseName }}</p>
                                <p><strong>Duration: </strong> {{ $course->duration }}</p>
                                <p><strong>Study Type:</strong> {{ $course->studyType }}</p>
                                <p><strong>Fees Local:</strong> {{ $course->feesLocal }}</p>
                                <p><strong>Fees International:</strong> {{ $course->feesInternational }}</p>
                                <p><strong>Study Level:</strong> {{ $course->studyLevel }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
