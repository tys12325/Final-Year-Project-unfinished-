@extends('layout.app')

@section('title', 'User Profile')
@section('head')
@vite(['resources/css/user/userProfile.css'])
@endsection

@section('content')
<a href="#" id="backButton"><i class="fas fa-chevron-left fa-2x"></i></a>
<div id="leftContent">

    <h2 id="userDash">User Dashboard</h2>
    <hr>
    <nav>
        <ul>
            <div class="nav-highlight"></div> 
            <li>
                <a href="{{ route('userProfile') }}" class="{{ Request::routeIs('userProfile') ? 'active' : '' }}">
                    <img src="{{ asset('images/user.png') }}" alt="User Icon" />
                    <span>User Profile</span>
                </a>
            </li>
            <li>
                <a href="{{ route('favorites')}}" class="{{ Request::routeIs('userFavorite') ? 'active' : '' }}">
                    <img src="{{ asset('images/favorite.png') }}" alt="User Icon" />
                    <span>Favorite</span>
                </a>
            </li>
            <li>
                <a href="{{ route('userNotification') }}" class="{{ Request::routeIs('userNotification') ? 'active' : '' }}">
                    <img src="{{ asset('images/bell.png') }}" alt="User Icon" />
                    <span>Notification</span>
                </a>
            </li>
            <li>
                <a href="{{ route('userUpcomingEvent') }}" class="{{ Request::routeIs('userUpcomingEvent') ? 'active' : '' }}">
                    <img src="{{ asset('images/upcoming.png') }}" alt="User Icon" />
                    <span>Upcoming</span>
                </a>
            </li>
            <li>
                <a href="{{ route('userSetting') }}" class="{{ Request::routeIs('userSetting') ? 'active' : '' }}">
                    <img src="{{ asset('images/setting.png') }}" alt="User Icon" />
                    <span>Setting</span>
                </a>
            </li>
        </ul>
    </nav>


</div>
<div id="rightContent">
    <div class="profile-container text-center">         
        <img src="{{ asset('storage/' . $user->fileInput) }}" class="profile-img" id="profileImage"
             onerror="this.src='{{ asset('images/profileImage.jpeg') }}'">

    </div>

   <div>
    <form>
        <label for="userName">User Name: </label>
        <input type="text" name="userName" id="userName" value="{{ $user->name }}" disabled><br>

        <label for="EmailAdd">Email Address: </label>
        <input type="text" name="EmailAdd" id="EmailAdd" value="{{ $user->email }}" disabled>

        @if ($user->email_verified_at)
            <span class="verified-badge">✔ Verified</span>
        @else
            <span class="not-verified">❌ Not Verified</span>
        @endif
        <br>

        <label for="PhoneNum">Phone Number: </label>
        <input type="text" name="PhoneNum" id="PhoneNum" value="{{ $user->phone }}" disabled><br>
    </form>
</div>



</div>
@endsection
@section('scripts')
<script>
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
</script>
@endsection









