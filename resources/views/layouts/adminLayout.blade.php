<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/adminDashboard.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Navbar */
        .nav nav {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .nav-title h2 {
            margin: 0;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #222;
            color: white;
            padding: 20px;
            position: fixed;
            left: 0;
            top: 50px;
            transition: transform 0.3s ease-in-out;
            z-index: 100;
        }

        .sidebar.hidden {
            transform: translateX(-250px);
        }

        /* Toggle Button */
        .toggle-btn {
            font-size: 20px;
            color: white;
            cursor: pointer;
            border: none;
            background: none;
            position: absolute;
            top: 8px;
            right: -43px;
            padding: 8px 12px;
            background-color: #444;
            border-radius: 5px;
            z-index: 200;
            
        }
        .toggle-btn:hover{
            opacity:0.8;
        }
        
        .sidebar.hidden .toggle-btn {
            right: -40px;
            background-color: #555;
        }

        /* Main Content */
        .dashboard-container {
            display: flex;
            margin-top: 58px;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-left: 270px;
            transition: margin-left 0.3s ease-in-out;
            background-color: #f5f5f5
            
        }

        .main-content.expanded {
            margin-left: 50px;

        }
        .noline > a:hover {
            text-decoration: none !important; /* Ensures no underline on hover */
            opacity:0.7; /* Change to any color you like */
        }
    </style>
</head>
<body>
    <div class="nav">
        <nav>
            <div class="nav-title"><h2>GraduatedXplore</h2></div>
        </nav>
    </div>


    <div class="dashboard-container">
        <div class="sidebar" id="sidebar">
            <button class="toggle-btn" onclick="toggleSidebar()">三</button>
            <div class="noline">
                <a href="{{ route('adminDashboard') }}"> <div><strong><h3>Admin Dashboard</h3></strong></div></a>
            </div>
            <a href="{{ route('universities.create') }}">Add University</a>
            <a href="{{ route('universities.index') }}">University (View)</a>
            <a href="{{ route('courses.create') }}">Add Programme</a>
            <a href="{{ route('courses.index') }}">Programme (View)</a>  
            <a href="{{ url('/import') }}">File Upload CSV</a>
            <a href="{{ url('/report') }}">University & Programme Report</a>
            <a href="{{ url('/') }}">UserSite Preview</a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </div>

        <div class="main-content" id="main-content">
            @yield('content')
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('main-content');

            sidebar.classList.toggle('hidden');
            content.classList.toggle('expanded');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
</body>
</html>
