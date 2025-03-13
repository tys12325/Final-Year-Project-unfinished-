@extends('layouts.adminLayout')

@section('content')
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .container {
            margin-top:50px;
            background: #fff;
            padding: 30px; /* More padding */
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 800px; /* Increase width */
            text-align: center;
            font-size: 18px; /* Increase overall text size */
        }

        /* Welcome Message Styling */
        .welcome-card {
            max-width: 600px;
            margin: 30px auto 20px;
            padding: 20px;
            background: linear-gradient(135deg, #f4f4f4, #eaeaea); /* Soft gray gradient */
            color: #111;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .welcome-card h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .welcome-card p {
            font-size: 16px;
            opacity: 0.8;
        }

        /* Profile Card Styling */
        .profile-card {
            max-width: 450px;
            margin: 20px auto;
            padding: 25px;
            background: #fdfdfd; /* Almost white */
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: left;
            border: 1px solid #ddd;
        }

        .profile-card h2 {
            text-align: center;
            color: #222; /* Dark title */
            margin-bottom: 20px;
        }

        .profile-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #ddd; /* Light gray border */
        }

        .profile-info:last-child {
            border-bottom: none;
        }

        .profile-info label {
            font-weight: bold;
            flex: 1;
            color: #333; /* Darker gray */
        }

        .profile-info span {
            flex: 2;
            color: #444; /* Medium dark text */
            font-size: 16px;
        }

        /* Buttons */
        .edit-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .edit-button:hover {
            background-color: #0056b3;
        }

        .reset-button {
            width: 100%;
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        .reset-button:hover {
            background-color: #c82333;
        }
        .save-button {
            margin-left:10px;
            background-color: #28a745; /* Green color */
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
            font-size: 14px;
        }

        .save-button:hover {
            background-color: #218838; /* Darker green on hover */
        }
        .username-input {
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        .username-input:focus {
            border-color: #007bff; /* Blue highlight on focus */
            outline: none;
        }
        .profile-info a{
            font-size: 14px;
            padding: 0px 0px;
            cursor: pointer;
            text-decoration: none;
        }
        .profile-info a:hover{
            background-color: #0056b3;
        }
        a{
            text-decoration: none;
        }
    </style>
    
    <div class="container">
        <div class="welcome-card">
            <h1>Welcome, {{ auth()->user()->name }}!</h1>
            <p>Manage your profile settings and keep your account up to date.</p>
        </div>

        <div class="profile-card">
            <h2>User Profile</h2>

            <div class="profile-info">
                <label>Username:</label>
                <span class="username-display">{{ auth()->user()->name }}</span>
                <!-- Editable Input (Hidden Initially) -->
                <input type="text" class="username-input" value="{{ auth()->user()->name }}" style="display: none; width: 70%; padding: 5px;">

                <!-- Buttons -->
                <button class="edit-button">Edit</button>
                <button class="save-button" style="display: none;">Save</button>
            </div>

            <div class="profile-info">
                <label>Email:</label>
                <span>{{ auth()->user()->email }}</span>
                <button class="edit-button"><a href="{{ route('email.update.form') }}" class="edit-button">Edit</a></button>
            </div>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                <button type="submit" class="reset-button">Reset Password</button>
            </form>



        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const editButton = document.querySelector(".edit-button");
            const saveButton = document.querySelector(".save-button");
            const usernameDisplay = document.querySelector(".username-display");
            const usernameInput = document.querySelector(".username-input");

            // Handle Edit Click
            editButton.addEventListener("click", function () {
                usernameDisplay.style.display = "none";
                usernameInput.style.display = "inline-block";
                editButton.style.display = "none";
                saveButton.style.display = "inline-block";
            });

            // Handle Save Click
            saveButton.addEventListener("click", function () {
                const newUsername = usernameInput.value;

                fetch("{{ route('update.username') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ username: newUsername })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        usernameDisplay.textContent = newUsername;
                        usernameDisplay.style.display = "inline-block";
                        usernameInput.style.display = "none";
                        editButton.style.display = "inline-block";
                        saveButton.style.display = "none";

                        // Show confirmation message
                        const messageDiv = document.createElement("div");
                        messageDiv.textContent = "Username updated successfully!";
                        messageDiv.style.color = "green";
                        messageDiv.style.marginTop = "10px";
                        document.querySelector(".profile-card").appendChild(messageDiv);


                    } else {
                        alert("Error updating username.");
                    }
                })

                .catch(error => console.error("Error:", error));
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
           // Reset Password Confirmation
           document.querySelector(".reset-button").addEventListener("click", function (event) {
               if (!confirm("A password reset link will be sent to your email. Do you want to proceed?")) {
                   event.preventDefault(); // Prevent form submission if user cancels
               }
           });

           // Change Email Confirmation
           document.querySelector(".edit-button a").addEventListener("click", function (event) {
               if (!confirm("You will be redirected to update email. Do you want to continue?")) {
                   event.preventDefault(); // Prevent navigation if user cancels
               }
           });
       });
    </script>

@endsection
