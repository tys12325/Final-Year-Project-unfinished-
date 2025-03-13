<div class="sidebar">
    <button class="toggle-btn" onclick="toggleSidebar()">三</button>
    <div><strong><h3>Admin Dashboard</h3></strong></div>
    <a href="{{ route('universities.index') }}">University (View)</a>
    <a href="{{ route('universities.create') }}">Add University</a>
    <a href="{{ route('courses.index') }}">Courses (View)</a>
    <a href="{{ route('courses.create') }}">Add Courses</a>
    <a href="{{ url('/import') }}">Import CSV</a>
    <a href="{{ url('/home') }}">UserSite Preview</a>
    <button class="logout-button">Log Out</button>
    <button class="delete-button">Delete Account</button>
</div>
