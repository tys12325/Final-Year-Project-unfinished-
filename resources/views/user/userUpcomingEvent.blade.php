@extends('layout.app')

@section('title', 'User Profile')
@section('head')
    @vite(['resources/css/user/userUpcomingEvent.css'])
@endsection

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
        <div id="Upcoming-container">
            <div class="searchBar">      
                <i class="fas fa-search"></i>
                <input type="search" id="searchBar" placeholder="Search University...">
            </div>
            <div>
                <h3>TARUMT</h3>
                <img src="src" alt="alt"/>
                
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
</script>
@endsection




  











