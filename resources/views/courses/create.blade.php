
@extends('layouts.adminLayout')
@section('title', 'Add Programme')

@section('content')
<style>
    /* Ensure consistent styling for all form controls */
    .form-control {
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    /* Style for select2 dropdowns */
    .select2-container .select2-selection--single {
        height: 38px !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important;
        padding-left: 12px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px !important;
    }    
</style>
<div class="container">
    <div id="deleteMessageContainer"></div>

    @if(session('duplicateError'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                showDeleteMessage(`{!! session('duplicateError') !!}`, 'danger');
            });
        </script>
    @endif

    @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                showDeleteMessage(`{{ session('success') }}`, 'success');
            });
        </script>
    @endif

    <h2>Add a New Programme</h2>

    <form action="{{ route('courses.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="courseName">Programme Name</label>
            <input type="text" name="courseName" id="courseName" class="form-control" required 
                pattern="^(?i)(Foundation|Diploma|Bachelor|Master|PhD) (in|of) .+$"
                title="Course Name must follow the format: 'Diploma in XXX' or 'Bachelor of xxx (Hons) in XXX'."
                placeholder="e.g., Diploma in xxx">
        </div>

        <div class="form-group">
            <label for="duration">Duration</label>
            <div class="input-group" style="max-width: 125px;"> 
                <input type="number" name="duration" id="duration" class="form-control" required 
                    min="1" max="5" pattern="^\d+$"
                    title="Please enter the number of years (e.g., 1, 2, 3, 4)." 
                    placeholder="3">
                <span class="input-group-text">Years</span>
            </div>
        </div>


        <div class="form-group">
            <label for="feesLocal">Fees (Local)</label>
            <div class="input-group">
                <span class="input-group-text">RM</span>
                <input type="text" name="feesLocal" id="feesLocal" class="form-control" required
                    pattern="^\d+$"
                    title="Fees must be in number only."
                    placeholder="e.g., 10000">
            </div>
        </div>

        <div class="form-group">
            <label for="feesInternational">Fees (International)</label>
            <div class="input-group">
                <span class="input-group-text">RM</span>
                <input type="text" name="feesInternational" id="feesInternational" class="form-control" required
                    pattern="^\d+$"
                    title="Fees must be a number only."
                    placeholder="e.g., 20000">
            </div>
        </div>


        <!-- Study Type Dropdown -->
        <div class="form-group">
            <label for="studyType">Study Type</label>
            <select name="studyType" id="studyType" class="form-control select2" required>
                <option value="">Select Study Type</option>
                @foreach ($studyTypes as $studyType)
                    <option value="{{ $studyType }}">{{ $studyType }}</option>
                @endforeach
            </select>
        </div>

        <!-- Study Level Dropdown -->
        <div class="form-group">
            <label for="studyLevel">Study Level</label>
            <select name="studyLevel" id="studyLevel" class="form-control select2" required>
                <option value="">Select Study Level</option>
                @foreach ($studyLevels as $studyLevel)
                    <option value="{{ $studyLevel }}">{{ $studyLevel }}</option>
                @endforeach
            </select>
        </div>

        <!-- University Dropdown -->
        <div class="form-group">
            <label for="uniName">University</label>
            <select name="uniName" id="uniName" class="form-control select2" required>
                <option value="">Select University</option>
                @foreach ($universities as $university)
                    <option value="{{ $university->uniName }}">{{ $university->uniName }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success mt-3">Add Programme</button>
        <a href="{{ route('courses.index') }}" class="btn btn-secondary mt-3">Back</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const courseNameInput = document.getElementById("courseName");
    const studyLevelSelect = document.getElementById("studyLevel");

    // Map study levels to their corresponding prefixes
    const prefixes = {
        "Foundation": "Foundation",
        "Diploma": "Diploma",
        "Bachelor": "Bachelor",
        "Master": "Master",
        "PhD": "PhD"
    };

    // Words to exclude from capitalization
    const excludeWords = ['in', 'or', 'of'];

    // Function to format the course name with proper capitalization
    function formatCourseName(courseName) {
        return courseName
            .split(' ') // Split the course name into words
            .map((word, index) => {
                // Exclude specific words from capitalization (except the first word)
                if (index !== 0 && excludeWords.includes(word.toLowerCase())) {
                    return word.toLowerCase(); // Keep excluded words lowercase
                }
                return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase(); // Capitalize the first letter of other words
            })
            .join(' '); // Join the words back into a single string
    }

    // Function to validate the course name based on the selected study level
    function validateCourseName() {
        const selectedLevel = studyLevelSelect.value;
        const currentCourseName = courseNameInput.value.trim();

        // If a valid study level is selected
        if (selectedLevel && prefixes[selectedLevel]) {
            const prefix = prefixes[selectedLevel].toLowerCase(); // Convert prefix to lowercase for case-insensitive comparison
            const courseNameLower = currentCourseName.toLowerCase(); // Convert course name to lowercase for case-insensitive comparison

            // Check if the course name contains the study level prefix
            if (!courseNameLower.includes(prefix)) {
                courseNameInput.setCustomValidity(`Course name must contain the study level: "${selectedLevel}".`);
            } else {
                courseNameInput.setCustomValidity(""); // Clear the validation message

                // Format the course name with proper capitalization
                const formattedCourseName = formatCourseName(currentCourseName);
                courseNameInput.value = formattedCourseName; // Update the input field with the formatted name
            }
        }
    }

    // Add event listeners for validation and formatting
    studyLevelSelect.addEventListener("change", validateCourseName);
    courseNameInput.addEventListener("input", validateCourseName);
});
    document.addEventListener("DOMContentLoaded", function () {
        $('#uniName').select2({
            placeholder: "Select or search a university",
            allowClear: true
        });
   
    document.addEventListener("DOMContentLoaded", function () {
        function enforceNumericInput(inputId) {
            document.getElementById(inputId).addEventListener("input", function (e) {
                this.value = this.value.replace(/[^0-9]/g, ""); // Remove non-numeric characters
            });
        }

        enforceNumericInput("feesLocal");
        enforceNumericInput("feesInternational");
    });


    });


    function showDeleteMessage(message, type) {
        let messageContainer = document.getElementById("deleteMessageContainer");

        let alertDiv = document.createElement("div");
        alertDiv.className = `alert alert-${type} position-fixed text-center shadow-lg`;
        alertDiv.style.zIndex = "1050";
        alertDiv.style.minWidth = "400px";
        alertDiv.style.maxWidth = "600px";
        alertDiv.style.padding = "20px";
        alertDiv.style.left = "50%";
        alertDiv.style.top = "50%";
        alertDiv.style.transform = "translate(-50%, -50%)";
        alertDiv.style.borderRadius = "10px";
        alertDiv.innerHTML = `
            ${message} 
            <button type="button" class="btn-close ms-3" onclick="this.parentElement.remove()"></button>
        `;

        messageContainer.appendChild(alertDiv);

        setTimeout(() => {
            alertDiv.remove();
        }, 7000);
    }
    
document.addEventListener("DOMContentLoaded", function () {
    // Initialize Study Type Dropdown with Tagging
    $('#studyType').select2({
        placeholder: "Select or add a study type",
        allowClear: true,
        tags: true, // Enable tagging
        createTag: function (params) {
            return {
                id: params.term, // Use the typed text as the ID
                text: params.term, // Use the typed text as the display text
                newTag: true // Mark as a new tag
            };
        }
    });

    // Initialize Study Level Dropdown with Tagging
    $('#studyLevel').select2({
        placeholder: "Select or add a study level",
        allowClear: true,
        tags: true, // Enable tagging
        createTag: function (params) {
            return {
                id: params.term, // Use the typed text as the ID
                text: params.term, // Use the typed text as the display text
                newTag: true // Mark as a new tag
            };
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    // Initialize select2 for studyType, studyLevel, and uniName dropdowns
    $('#studyType').select2({
        placeholder: "Select Study Type",
        allowClear: true
    });

    $('#studyLevel').select2({
        placeholder: "Select Study Level",
        allowClear: true
    });


});
</script>

@endsection
