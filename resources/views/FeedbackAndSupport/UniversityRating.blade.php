@extends('layout.app')

@section('title', 'Rate ' . $uni->uniName)

@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@vite(['resources/css/feedback/university_rating.css'])
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

@endsection


@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        Something went wrong. Please try again.
    </div>
@endif
 <a href="{{route('universities.indexes')}}" id="backButton"><i class="fas fa-chevron-left fa-2x"></i></a>
<div class="container">
    <h2>{{ $uni->uniName }}</h2>
    <p><strong>Location:</strong> {{ $uni->Address ?? 'N/A' }}</p>
    
    @auth
    <div class="card p-4 mt-3">
        <h4>Rate this university</h4>
        <form action="{{ route('ratings.store') }}" method="POST">
            @csrf
            <input type="hidden" name="uniID" value="{{ $uni->uniID }}">
            <div class="rating-container">
                <div class="mb-3">
                    <label class="form-label">Your Rating:</label><br>
                    <input type="hidden" name="rating" id="rating_val" value="0">
                    <span>Poor</span>
                    <span class="rating_stars rating_0">
                        
                        <span class='s' data-high='1'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                        <span class='s' data-high='2'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                        <span class='s' data-high='3'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                        <span class='s' data-high='4'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                        <span class='s' data-high='5'><i class="fa fa-star-o"></i><i class="fa fa-star-half-o"></i><i class="fa fa-star"></i></span>
                        <span class='r r1' data-rating='1' data-value='1'></span>
                        <span class='r r2' data-rating='2' data-value='2'></span>
                        <span class='r r3' data-rating='3' data-value='3'></span>
                        <span class='r r4' data-rating='4' data-value='4'></span>
                        <span class='r r5' data-rating='5' data-value='5'></span>
                        
                    </span>
                    <span>Excellent</span>
                </div>
            </div>
         <div class="comment-section">
                <div class="mb-3">
                    <label for="comment" class="form-label">Your Comment:</label>
                    <textarea id="comment" name="comment" rows="5" class="form-control" placeholder="Type something... (Max 500 words)"></textarea>
                    <p id="wordCount" class="text-muted">Words left: 500</p>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Submit Rating</button>
        </form>
    </div>
    @else
    <p class="mt-3"><a href="{{ route('login') }}">Login</a> to rate this university.</p>
    @endauth

     <div id="reviews-section">
            <h3 class="mt-4">Reviews</h3>
               @auth
                <div class="swiper-container">
                <div class="swiper-wrapper">
                    @foreach($uni->ratings as $rating)
                    <div class="swiper-slide">
                        <div class="review-card">
                            <div>
                                <strong>{{ $rating->user->name }}</strong><br>
                                <span class="text-muted">{{ $rating->created_at->diffForHumans() }}</span>
                                <div class="rating-display">
                                    @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $rating->rating)
                                    <i class="fa fa-star text-warning" style="color: #feb645;"></i>
                                    @else
                                    <i class="fa fa-star text-secondary" style="color: #ccc;"></i>
                                    @endif
                                    @endfor
                                </div>
                                
                                <p class="mt-2 comment-text"> 
                                   
                                    {{ Str::limit($rating->comment, 50, '...')?: 'No Comment...'  }}
                                     
                                </p>
                                @if(strlen($rating->comment) > 100)
                                        <a class="view-comment-btn" data-comment="{{ $rating->comment }}">Read More</a>
                                    @endif
                                    
                            </div>
                        </div>
                    </div>


                    @endforeach
                </div>
    
               
                <div class="swiper-button-prev">
                    
                </div>
                <div class="swiper-button-next">
                  
                </div>
                     <div class="swiper-pagination"></div>
                    @else
                    
                    <p class="mt-3">Login to see others review.</p>
                    
                    @endauth

             
                
            </div>
        </div>

</div>
    <div id="commentModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Full Comment</h3>
        <p id="full-comment-text"></p>
    </div>
</div>
@endsection



@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script> 
<script>


jQuery(document).ready(function ($) {
    $(".rating_stars span.r")
            .hover(
                    function () {
                        var rating = $(this).data("rating");
                        var value = $(this).data("value");
                        $(this)
                                .parent()
                                .attr("class", "")
                                .addClass("rating_stars")
                                .addClass("rating_" + rating);
                        highlight_star(value);
                    },
                    function () {
                        var rating = $("#rating").val();
                        var value = $("#rating_val").val();
                        $(this)
                                .parent()
                                .attr("class", "")
                                .addClass("rating_stars")
                                .addClass("rating_" + rating);
                        highlight_star(value);
                    }
            )
            .click(function () {
                var value = $(this).data("value");
                $("#rating_val").val(value);

                var rating = $(this).data("rating");
                $("#rating").val(rating);

                highlight_star(value);
            });

    var highlight_star = function (rating) {
        $(".rating_stars span.s").each(function () {
            var low = $(this).data("low");
            var high = $(this).data("high");
            $(this).removeClass("active-high").removeClass("active-low");
            if (rating >= high)
                $(this).addClass("active-high");
            else if (rating === low)
                $(this).addClass("active-low");
        });
    };
});
document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.getElementById('comment');
    const charCountDisplay = document.getElementById('wordCount');

    textarea.addEventListener('input', function () {
        const charCount = textarea.value.length;

        if (charCount > 500) {

            textarea.value = textarea.value.substring(0, 500);
            charCountDisplay.textContent = 'Characters left: 0';
            charCountDisplay.classList.add('warning');
        } else {
            charCountDisplay.textContent = `Characters left: ${500 - charCount}`;
            if (charCount >= 450) {
                charCountDisplay.classList.add('warning');
            } else {
                charCountDisplay.classList.remove('warning');
            }
        }
    });
    var swiper = new Swiper(".swiper-container", {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: {{ $uni->ratings->count() > 2 ? 'true' : 'false' }},
        autoHeight: true,
        autoplay: {
        delay: 5000,
        pauseOnMouseEnter: true, 
        },
        grabCursor: true, 
        keyboard: {
            enabled: true, 
        },
        navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            768: {
                slidesPerView: 2,
                spaceBetween: 30
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 40
            }
        }
    });
    
   
    const modal = document.getElementById("commentModal");
    const modalText = document.getElementById("full-comment-text");
    const closeModal = document.querySelector(".close");

    document.querySelectorAll(".view-comment-btn").forEach(function (button) {
        button.addEventListener("click", function () {
            modalText.textContent = this.getAttribute("data-comment");
            modal.style.display = "block";
        });
    });

    closeModal.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

});
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