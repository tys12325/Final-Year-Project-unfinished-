@extends('layout.app')

@section('title', 'User Details')
@section('head')
@vite(['resources/css/user/user_profile_details.css'])


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

@endsection


@section('content')
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
<a href="{{ route('userSetting') }}" id="backButton"><i class="fas fa-chevron-left fa-2x"></i></a>
<div class="AppContent">
    <h2>Edit Profile</h2>
    <form id="profileForm" action="{{ route('profile.update', $user->id) }}" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
        @csrf
        @method('POST')

        <!-- Profile Picture Section -->
        <div class="profile-container" onclick="document.getElementById('fileInput').click();">
            <img src="{{ asset('storage/' . $user->fileInput) }}" class="profile-picture"
                 onerror="this.src='{{ asset('images/profileImage.jpeg') }}'">
            <input type="file" name="fileInput" id="fileInput" accept=".png, .jpg, .jpeg" class="file-input" onchange="previewImage(event)">
        </div>


        <div class="insideContent">
            <div class="innerContent">

                <label for="name">Full Name: </label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" >
                @if ($user->ic)
                <label for="ic">IC Number: </label>
                <input type="text" name="ic" id="ic" value="{{ old('ic', $user->ic) }}" disabled>
                @else
                <div>
                    <label for="ic">IC Number: </label>
                    <input type="text" name="ic" id="ic" value="{{ old('ic', $user->ic) }}" >
                    <p style="color: red; font-size: 14px; margin-top: 1px;">IC Number cannot be edited after updated.</p>
                </div>
                @endif

                <label for="BirthDayDate">Birthday: </label>
                <input type="date" name="BirthDayDate" id="BirthDayDate" value="{{ old('BirthDayDate', $user->BirthDayDate) }}" >

                <label for="gender">Gender: </label>
                <select name="gender" id="gender" >
                    <option value="" disabled>Select Gender</option>
                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                </select>

                <label for="certificate">Certificate: </label>
                <input type="file" id="fileUpload" name="certificate" accept=".pdf,.jpg,.png">
                <br>

                @if($user->certificate)
                <span>
                    Current file: 
                    <a href="{{ asset('storage/' . $user->certificate) }}" target="_blank">
                        {{ basename($user->certificate) }}
                    </a>
                </span>

                <!-- Show image preview only if it's an image file -->
                @if(in_array(pathinfo($user->certificate, PATHINFO_EXTENSION), ['jpg', 'png', 'jpeg']))
                <br>
                <img src="{{ asset('storage/' . $user->certificate) }}" alt="Certificate Preview" 
                     style="max-width: 100px; max-height: 100px; border: 1px solid #ccc;">
                @endif
                @else
                <span>No file selected</span>
                @endif

            </div>

            <div class="innerContentRight">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}">
                <input type="text" id="address2" name="address2" value="{{ old('address2', $user->address2) }}">
                <input type="text" id="address3" name="address3" value="{{ old('address3', $user->address3) }}">

                <label for="email">Email: </label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" disabled>

                <label for="phone">H/P:</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" disabled>

                <label for="education">Education:</label>
                <select id="education" name="education">
                    <option value="primary" {{ $user->education == 'primary' ? 'selected' : '' }}>Primary School</option>
                    <option value="secondary" {{ $user->education == 'secondary' ? 'selected' : '' }}>Secondary School</option>
                    <option value="diploma" {{ $user->education == 'diploma' ? 'selected' : '' }}>Diploma</option>
                    <option value="degree" {{ $user->education == 'degree' ? 'selected' : '' }}>Degree</option>
                </select>

                <label for="nationality">Nationality:</label>
                <select id="nationality" name="nationality" onchange="checkNationality()" >
                    <option value="malaysia" {{ $user->nationality == 'malaysia' ? 'selected' : '' }}>Malaysia</option>
                    <option value="singapore" {{ $user->nationality == 'singapore' ? 'selected' : '' }}>Singapore</option>
                    <option value="indonesia" {{ $user->nationality == 'indonesia' ? 'selected' : '' }}>Indonesia</option>
                    <option value="other" {{ $user->nationality == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                <input type="text" id="otherNationality" name="otherNationality" value="{{ old('otherNationality', $user->otherNationality) }}" placeholder="Enter your nationality" style="{{ $user->nationality == 'other' ? 'display:block' : 'display:none' }};">
            </div>
        </div>

        <button type="submit" class="enroll-btn">Update</button>
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
                        var reader = new FileReader();
                        reader.onload = function () {
                            var output = document.querySelector(".profile-picture");
                            output.src = reader.result;
                        };
                        reader.readAsDataURL(event.target.files[0]);
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

                        let birthday = document.getElementById("BirthDayDate").value;

                        let certificate = document.getElementById("fileUpload").files[0];
                        let profile_picture = document.getElementById("fileInput").files[0];

                        
                        let icPattern = /^[0-9]{6}-[0-9]{2}-[0-9]{4}$/;
                        if(!ic){
                            return true;
                        }else if (!icPattern.test(ic)) {
                            alert("IC Number must be in the format XXXXXX-XX-XXXX.");
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








