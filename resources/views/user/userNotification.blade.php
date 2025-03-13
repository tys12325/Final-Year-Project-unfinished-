
@extends('layout.app')

@section('title', 'User Notification')
@section('head')
    @vite(['resources/css/user/userNotification.css'])
@endsection

@section('content')
        <a href="#" id="backButton"><i class="fas fa-chevron-left fa-2x"></i></a>
        <div id="LeftContentNoti">
            
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
        <div id="notification-container">
            <div id="rightsideNotification">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="search" id="searchBar" placeholder="Search notification...">
                </div><br>
                <div class="select-all">
                    <input type="checkbox" id="selectAll" > 
                    <label for="selectAll">Select All</label>
                </div>
            </div>
            
            <div class="notification-list">

                <div class="notification-item">
                    <input type="checkbox">
                    <div class="notification-content">
                        <i class="far fa-star"></i>
                        <strong>announcement</strong>
                         <span>New Announcement: TARC......................................................................."</span>
                    </div>

                </div>
                <div class="notification-item">
                    <input type="checkbox">
                    <div class="notification-content">
                        <i class="far fa-star"></i>
                        <strong>announcement</strong>
                         <span>New Announcement: TARC......................................................................."</span>
                    </div>

                </div>
            </div>
            
            
     
            
        </div>
@endsection
@section('scripts')
<script>
 document.addEventListener("DOMContentLoaded", function () {
 
            const selectAllCheckbox = document.getElementById("selectAll");
            const notificationCheckboxes = document.querySelectorAll(".notification-item input[type='checkbox']");

            selectAllCheckbox.addEventListener("change", function () {
               notificationCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
               });
            });

    
            const starIcons = document.querySelectorAll(".notification-content i");

            starIcons.forEach(star => {
                star.addEventListener("click", function () {
                    star.classList.toggle("fas");  
                    star.classList.toggle("far");  
                    star.style.color = star.classList.contains("fas") ? "gold" : "#999";
                });
            });
        });

       

</script>
@endsection



