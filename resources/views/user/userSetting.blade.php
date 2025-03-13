@extends('layout.app')

@section('title', 'User Profile')
@section('head')
    @vite(['resources/css/user/userSetting.css'])
@endsection
@if(session('success'))
<div class="alert alert-success">
        {{ session('success') }}
</div>
  
@endif
@if(session('info'))
<div class="alert alert-success">
        {{ session('info') }}
</div>
    
@endif
@if(session('error'))
<div class="alert alert-danger">
        {{ session('error') }}
</div>   
@endif
@section('content')
    <a href="#" id="backButton"><i class="fas fa-chevron-left fa-2x"></i></a>
        <div id="leftContener">
            
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
                        <a href="{{ route('userFavorite')}}" class="{{ Request::routeIs('userFavorite') ? 'active' : '' }}">
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
        <div class="Setting-container">
            <div class="title">
                <h3 class='setting-H3'>System</h3>
                
            </div>
            <div class='Setting-list'>
                <div class='setting-content'>
                    <p class='settingContent-cell'>Notification</p>
                    <label class="switch">
                        <input type="checkbox" checked="true">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class='setting-content'>
                    <p class='settingContent-cell'>Sound</p>
                    <label class="switch">
                        <input type="checkbox" checked="true">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class='setting-content'>
                    <p class='settingContent-cell'>Upcoming Event</p>
                    <label class="switch">
                        <input type="checkbox" checked="true">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            <div class="title">
                <h3 class="setting-H3">Account</h3>
                
            </div>
            <div class='Setting-list'>              
                <div class="setting-content">
                    <span>2-step Verification</span>    
                    <a href="#}" class="icon-btn">
                        <i class="fas fa-key"></i>
                    </a>
                </div>
                <div class="setting-content">
                    <span>Password Reset</span>
                    <a href="{{ route('dashboard.password.reset') }}" class="icon-btn">
                        <i class="fas fa-sync"></i>
                    </a>
                </div>
                <div class="setting-content">
                    <span>Change Account Details</span>
                    
                    <a href="{{ route('profile.edit') }}" class="icon-btn">
                        <i class="fas fa-user-edit"></i>
                    </a>
                </div>

                <div class="setting-content">
                    <span>Verify Email</span>
                    <a href="{{ route('verification.form') }}" class="icon-btn">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
                <div class="setting-content">
                    <span>Verify Phone Number</span>
                    <a href="{{ route('otp') }}" class="icon-btn">
                        <i class="fas fa-phone"></i>
                    </a>
                </div>

                
            </div>
            <div class="logoutBtn">
                <button class="logout-btn" onclick="logoutAjax()">
                    <i class="fas fa-sign-out-alt"></i> LOG OUT
                </button>
            </div>
            
        </div>
@endsection
@section('scripts')
<script>
   function logoutAjax() {
            if (confirm("Are you sure you want to Log Out?")) {
            fetch("{{ route('logout') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => window.location.href = "{{ route('login') }}"); 
            }
        }
setTimeout(function() {
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




  








