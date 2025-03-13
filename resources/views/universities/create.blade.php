
@extends('layouts.adminLayout')

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

    <h2>Add New University</h2>

    <form action="{{ route('universities.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <div class="col">
                <label for="uniName" class="form-label">University Name</label>
                <input type="text" id="uniName" name="uniName" class="form-control" required>
            </div>
            <div class="col">
                <label for="Address" class="form-label">Address</label>
                <input type="text" id="Address" name="Address" class="form-control" required>
            </div>
            <div class="col">
                <label for="ContactNumber" class="form-label">Contact Number</label>
                <input type="text" id="ContactNumber" name="ContactNumber" class="form-control" placeholder="+60 12-345 6789" required>
                <div id="phoneError" style="color: red; font-weight: bold; margin-top: 5px; display: none;"></div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="OperationHour" class="form-label">Operation Hours</label>
                <input type="text" id="OperationHour" name="OperationHour" class="form-control" placeholder="Monday to Friday, 8:30 AM – 5:30 PM" required>
            </div>
            <div class="col">
                <label for="DateOfOpenSchool" class="form-label">University Opening Date</label>
                <input type="date" id="DateOfOpenSchool" name="DateOfOpenSchool" class="form-control" required>
            </div>
            <div class="col">
                <label for="Category" class="form-label">Category Of Institution</label>
                <select id="Category" name="Category" class="form-control select2" required>
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="Description" class="form-label">Description</label>
                <input type="text" id="Description" name="Description" class="form-control" required>
            </div>
            <div class="col">
                <label for="Founder" class="form-label">Founder</label>
                <input type="text" id="Founder" name="Founder" class="form-control" required>
            </div>
            <div class="col">
                <label for="EstablishDate" class="form-label">Establishment Date</label>
                <input type="date" id="EstablishDate" name="EstablishDate" class="form-control" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="Ranking" class="form-label">Ranking (Optional)</label>
                <input type="number" id="Ranking" name="Ranking" class="form-control">
            </div>
            <div class="col">
                <label for="NumOfCourses" class="form-label">Number of Programmes (Optional)</label>
                <input type="number" id="NumOfCourses" name="NumOfCourses" class="form-control" disabled>
            </div>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">University Image Path</label>
            <input type="text" id="imagePath" name="image" class="form-control" placeholder="Enter image path (e.g., imageName.png)">
            <div id="imageError" style="color: red; font-weight: bold; margin-top: 5px; display: none;"></div>
        </div>
        <button type="submit" class="btn btn-primary">Save University</button>
        <a href="{{ route('universities.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        var imageInput = document.querySelector('input[name="image"]');
        var imagePath = imageInput.value.trim(); // Get and trim the image path input
        var phoneInput = document.querySelector('input[name="ContactNumber"]');
        var phoneNumber = phoneInput.value.trim(); // Get and trim phone number input
        var errorMessageContainer = document.getElementById('imageError'); // Image error message
        var phoneErrorContainer = document.getElementById('phoneError'); // Phone number error message

        // Clear previous errors
        errorMessageContainer.textContent = '';
        errorMessageContainer.style.display = 'none';
        phoneErrorContainer.textContent = '';
        phoneErrorContainer.style.display = 'none';
        // ✅ Phone number validation (Malaysia format)
        var phoneRegex = /^(01[0-9]-?\d{7,8}|0[3-9]-?\d{7,8})$/;

        if (!phoneRegex.test(phoneNumber)) {
            phoneErrorContainer.textContent = '❌ Invalid phone number format. \nExample: 012-3456789 or 03-12345678';
            phoneErrorContainer.style.display = 'block';
            event.preventDefault();
            return;
        }
        // ✅ Image validation: Only allow .png
        if (imagePath) {
            var extension = imagePath.split('.').pop().toLowerCase();

            if (extension && extension !== 'png') {
                errorMessageContainer.textContent = '❌ Only .png images are allowed.';
                errorMessageContainer.style.display = 'block';
                event.preventDefault();
                return;
            }

            if (!imagePath.startsWith('images/')) {
                imagePath = 'images/' + imagePath;
            }

            if (!imagePath.includes('.')) {
                imagePath += '.png';
            }

            imageInput.value = imagePath;
        }


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
        var establishDateInput = document.getElementById("EstablishDate");

        // Set max date to today
        var today = new Date().toISOString().split("T")[0];
        establishDateInput.setAttribute("max", today);

        // Prevent submission if date is in the future
        document.querySelector("form").addEventListener("submit", function (event) {
            if (establishDateInput.value > today) {
                alert("❌ Establishment date cannot be in the future.");
                event.preventDefault();
            }
        });
    });
    
document.addEventListener("DOMContentLoaded", function () {
    console.log("Initializing select2 for Category dropdown"); // Debugging
    $('#Category').select2({
        placeholder: "Select or add a category",
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
</script>
@endsection
