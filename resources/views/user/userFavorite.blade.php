@extends('layout.app')

@section('title', 'User Favorite')
@section('head')
    @vite(['resources/css/user/userFavorite.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        <div id="rightContener">
            <div class="searchBar">      
                <i class="fas fa-search"></i>
                <input type="search" id="searchBar" placeholder="Search University...">
            </div>
            <br>
            <div id="favoriteList">
    @forelse($favorites as $favorite)
        <div class="ListContainer" id="favorite-{{ $favorite->courseID }}">
            <div class="favorite-item">
                <img src="{{ asset($favorite->course->university->image_path) }}" 
                     class="listImages">
                <div class="content">
                    <h3 class="Title">
                        {{ $favorite->course->courseName }}
                        <button class="bookmark-btn" 
                                data-course-id="{{ $favorite->courseID }}">
                            <img src="{{ asset('images/bookmark-filled.png') }}" 
                                 class="bookmark-icon">
                        </button>
                    </h3>
                    <p class="paragraph">
                        {{ Str::limit($favorite->course->university->description, 200) }}
                    </p>
                </div>
            </div>
        </div>
    @empty
        <div class="no-favorites">
            <p>No favorites found</p>
        </div>
    @endforelse
</div>
        
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
//    document.querySelectorAll('.bookmark-btn').forEach(button => {
//button.addEventListener('click', function(e) {
//    e.preventDefault();
//    const isSaved = this.dataset.isSaved === 'true';
//    const itemId = this.dataset.itemId;
//        
//       
//    this.classList.toggle('saved', !isSaved);
//    this.dataset.isSaved = !isSaved;
//        
//  
//    const icon = this.querySelector('.bookmark-icon');
//    icon.src = isSaved ? 
//        "{{ asset('images/bookmark.png') }}" : 
//        "{{ asset('images/bookmark-filled.png') }}";
//
//    fetch(`/toggle-save/${itemId}`, {
//        method: 'POST',
//        headers: {
//            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
//            'Content-Type': 'application/json'
//        }
//    })
//    .then(response => response.json())
//    .then(data => {
//        if (!data.success) {
//        
//            this.classList.toggle('saved', isSaved);
//            icon.src = isSaved ? 
//                "{{ asset('images/bookmark-filled.png') }}" : 
//                "{{ asset('images/bookmark.png') }}";
//        }
//    });
//});
//});



document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e) {
        if (e.target.closest('.bookmark-btn')) {
            e.preventDefault();
            handleFavoriteToggle(e.target.closest('.bookmark-btn'));
        }
    });
});

function handleFavoriteToggle(button) {
    const courseId = button.dataset.courseId;
    const listItem = document.getElementById(`favorite-${courseId}`);

   
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(`/favorites/${courseId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Network error');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            listItem.style.transition = 'opacity 0.3s, transform 0.3s';
            listItem.style.opacity = '0';
            listItem.style.transform = 'translateX(-100px)';

            setTimeout(() => {
                listItem.remove();
                if (document.querySelectorAll('.ListContainer').length === 0) {
                    document.getElementById('favoriteList').innerHTML = `
                        <div class="no-favorites">
                            <p>No favorites found</p>
                        </div>
                    `;
                }
            }, 300);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.innerHTML = '<img src="/images/bookmark-filled.png" class="bookmark-icon">';
        alert('Failed to remove favorite. Please try again.');
    });
}

</script>
@endsection