@extends('layout.app')

@section('title', 'FeedBack Support')
@section('head')
@vite(['resources/css/application.css'])


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

@endsection
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@section('content')
<div class="AppContent">
    <h2>Application Form</h2>

    <!-- Profile Picture Section -->


    <!-- Form Fields -->
    <form id="applicationForm" action="{{route('application.store')}}" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
        @csrf
        <div class="profile-container">
            <img src="" alt="Profile Picture" class="profile-img" id="profileImage">
            <input type="file" name="fileInput" id="fileInput" accept=".png, .jpg, .jpeg" class="file-input" onchange="previewImage(event)">
            <label for="fileInput" class="upload-label">Update Profile Picture</label>
        </div>
        <div class="insideContent">
            <div class="innerContent">
                <label for="lastName">Last Name: </label>
                <input type="text" name="lastName" id="lastName" required>

                <label for="firstName">First Name: </label>
                <input type="text" name="firstName" id="firstName" required>

                <label for="ic">IC Number: </label>
                <input type="text" name="ic" id="ic" required>

                <label for="BirthDayDate">Birthday: </label>
                <input type="date" name="BirthDayDate" id="BirthDayDate" required>

                <label for="gender">Gender: </label>
                <select name="gender" id="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>

                <label for="certificate">Certificate: </label>
                <label for="fileUpload" class="file-label" id="uploadIcon">
                    <i class="fas fa-file-upload"></i> 
                </label>
                <input type="file" id="fileUpload" name="certificate" accept=".pdf,.jpg,.png" hidden>
                <span id="fileName">No file selected</span>
            </div>

            <div class="innerContentRight">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
                <input type="text" id="address2" name="address2">
                <input type="text" id="address3" name="address3">

                <label for="phone">H/P:</label>
                <input type="text" id="phone" name="phone" required>

                <label for="education">Education:</label>
                <select id="education" name="education" required>
                    <option value="primary">Primary School</option>
                    <option value="secondary" selected>Secondary School</option>
                    <option value="diploma">Diploma</option>
                    <option value="degree">Degree</option>
                </select>

                <label for="nationality">Nationality:</label>
                <select id="nationality" name="nationality" onchange="checkNationality()" required>
                    <option value="malaysia" selected>Malaysia</option>
                    <option value="singapore">Singapore</option>
                    <option value="indonesia">Indonesia</option>
                    <option value="other">Other</option>
                </select>
                <input type="text" id="otherNationality" name="otherNationality" placeholder="Enter your nationality" style="display: none;">
                <label for="university">University:</label>
                <select id="university" name="university" class="form-control select2" required>
                    <option value="" disabled selected>Select University</option>
                    @foreach ($universities as $university)
                    <option value="{{ $university->id }}">{{ $university->name }}</option>
                    @endforeach
                </select>



            </div>
        </div>


        <button type="submit" class="enroll-btn">Enroll</button>
    </form>
</div>
@endsection
@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
                    document.addEventListener("DOMContentLoaded", function () {
                        console.log("DOM fully loaded");

                        if (typeof jQuery == 'undefined') {
                            console.error("jQuery is NOT loaded.");
                            return;
                        }

                        if (!$.fn.select2) {
                            console.error("Select2 is NOT loaded.");
                            return;
                        }

                        $('#university').select2({
                            placeholder: "Search University",
                            allowClear: true
                        });

                        console.log("Select2 initialized successfully.");
                    });

                    function previewImage(event) {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function () {
                                document.getElementById('profileImage').src = reader.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    }

                    document.getElementById("fileUpload").addEventListener("change", function () {
                        let fileName = this.files.length > 0 ? this.files[0].name : "No file selected";
                        document.getElementById("fileName").textContent = fileName;

                        let fileLabel = document.querySelector(".file-label");
                        if (this.files.length > 0) {
                            fileLabel.classList.add("uploaded");
                        } else {
                            fileLabel.classList.remove("uploaded");
                        }
                    });

                    function checkNationality() {
                        let nationalitySelect = document.getElementById("nationality");
                        let otherNationalityInput = document.getElementById("otherNationality");

                        if (nationalitySelect.value === "other") {
                            otherNationalityInput.style.display = "block";
                            otherNationalityInput.setAttribute("required", "true");
                        } else {
                            otherNationalityInput.style.display = "none";
                            otherNationalityInput.removeAttribute("required");
                        }
                    }
                    function validateForm() {
                        let ic = document.getElementById("ic").value;
                        let phone = document.getElementById("phone").value;
                        let birthday = document.getElementById("BirthDayDate").value;
                        let gender = document.getElementById("gender").value;
                        let certificate = document.getElementById("fileUpload").files[0];  // Fixed ID
                        let profile_picture = document.getElementById("fileInput").files[0];  // Fixed ID

                        
                        let icPattern = /^[0-9]{6}-[0-9]{2}-[0-9]{4}$/;
                        if (!icPattern.test(ic)) {
                            alert("IC Number must be in the format XXXXXX-XX-XXXX.");
                            return false;
                        }

                        
                        let phonePattern = /^(\+?60|0)[1-9]\d{8,9}$/;
                        if (!phonePattern.test(phone)) {
                            alert("Invalid phone number format.");
                            return false;
                        }

                       
                        let today = new Date();
                        let birthDate = new Date(birthday);
                        let age = today.getFullYear() - birthDate.getFullYear();
                        let monthDiff = today.getMonth() - birthDate.getMonth();

                        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                            age--;
                        }

                        if (age < 15) {
                            alert("You must be at least 15 years old.");
                            return false;
                        }
                        if (age > 60) {
                            alert("You must enter a valid birthday date.");
                            return false;
                        }

                        if (gender === "") {
                            alert("Please select a gender.");
                            return false;
                        }

                        if (!certificate) {
                            alert("Pls select a certificate.");
                            return false;
                        }
                        if (certificate) {
                            let allowedExtensions = ["pdf", "jpg", "png"];
                            let fileExt = certificate.name.split('.').pop().toLowerCase();
                            if (!allowedExtensions.includes(fileExt)) {
                                alert("Invalid certificate file format. Only PDF, JPG, PNG allowed.");
                                return false;
                            }
                            if (certificate.size > 2 * 1024 * 1024) { // 2MB limit
                                alert("Certificate file size should not exceed 2MB.");
                                return false;
                            }

                        }

                      
                        if (profile_picture) {
                            let allowedImageExtensions = ["jpg", "png", "jpeg"];
                            let imageExt = profile_picture.name.split('.').pop().toLowerCase();
                            if (!allowedImageExtensions.includes(imageExt)) {
                                alert("Invalid profile picture format. Only JPG, PNG allowed.");
                                return false;
                            }
                            if (profile_picture.size > 2 * 1024 * 1024) { // 2MB limit
                                alert("Profile picture size should not exceed 2MB.");
                                return false;
                            }
                        }

                        return true; 
                    }
                    function updateNationality() {
                        var nationalitySelect = document.getElementById("nationality");
                        var otherNationalityInput = document.getElementById("otherNationality");

                        if (nationalitySelect.value === "other") {
                            nationalitySelect.value = otherNationalityInput.value;
                        }
                    }
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








