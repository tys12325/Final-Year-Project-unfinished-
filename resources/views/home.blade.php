<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    /* Background video container */
    .video-container {
        position: fixed; /* Ensures it covers the full screen */
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: -1; /* Pushes it behind other content */
        overflow: hidden;
    }

    /* Video styling */
    .video-container video {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Ensures the video covers the entire background */
    }

    /* Centering the form */
.form-container {
    background: rgba(0, 0, 0, 0.9); /* 90% black (10% transparent) */
    color: white; /* Ensure text remains visible */
    padding: 20px;
    border: 4px solid white; /* White border for contrast */
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(255, 255, 255, 0.2);
    width: 90%;
    max-width: 800px;
}

/* Ensure text & inputs are visible */
.form-container h1,
.form-container label {
    color: white;
}

.form-select, 
.form-select option {
    border: 1px solid white;
}

</style>

<!-- Background Video -->
<div class="video-container">
    <video autoplay loop muted playsinline>
        <source src="{{ asset('videos/filterbg.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>

<!-- Centered Filter Form -->
<div class="d-flex justify-content-center align-items-center vh-100">
    <form action="{{ route('filter.results') }}" method="GET" class="form-container shadow">
        <h1 class="fw-bold text-center mb-4">Find Your Courses</h1>
        <p class="text-center text-light mb-4">Select your preferences to get the best recommendations.</p>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="uni" class="fw-semibold">University</label>
                <select name="uni" id="uni" class="form-select">
                    <option value="">Select University</option>
                    @foreach($universities as $university)
                        <option value="{{ $university }}" {{ request('uni') == $university ? 'selected' : '' }}>
                            {{ $university }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label for="city" class="fw-semibold">City</label>
                <select name="city" id="city" class="form-select">
                    <option value="">Select City</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label for="level" class="fw-semibold">Study Level</label>
                <select name="level" id="level" class="form-select">
                    <option value="">Select Level</option>
                    @foreach($levels as $level)
                        <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>
                            {{ $level }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-dark px-4 py-2">Find Courses</button>
        </div>
    </form>
</div>
