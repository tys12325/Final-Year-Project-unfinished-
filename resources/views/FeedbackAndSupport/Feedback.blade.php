
@extends('layout.app')

@section('title', 'FeedBack')
@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@vite(['resources/css/feedback/feedback.css'])
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

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


<a href="{{ route('feedBackSupport') }}" id="backButton"><i class="fas fa-chevron-left fa-2x"></i></a>
<div class="feedbackContainer">

    <div class="header">Feedback</div>
    @auth
    
    <form action="{{ route('feedback.store') }}" method="post">
        @csrf
        <div class="QuestionContainer">
            <p>General Feedback Questions:</p>
            <div class="questions">
                <label>1) Did you think the search filters (such as location, courses, fees) useful?<span class="required"> *</span></label>
                <input type="radio" name="filters1" value="1" required>Yes
                <input type="radio" name="filters1" value="0" required>No
            </div>
            <div class="questions">
                <label>2) Did the system provide enough options to compare different universities?<span class="required"> *</span></label>
                <input type="radio" name="filters2" value="1" required>Yes
                <input type="radio" name="filters2" value="0" required>No
            </div>
            <div class="questions">
                <label>3) Were you able to find all the information you were looking for?<span class="required"> *</span></label>
                <input type="radio" name="filters3" value="1" required>Yes
                <input type="radio" name="filters3" value="0" required>No
            </div>
            <div class="questions">
                <label >4) Would you like to see more filters or features in the system (e.g., student support services)?<span class="required"> *</span></label>
                <input type="radio" name="filters4" value="1" required>Yes
                <input type="radio" name="filters4" value="0" required>No
            </div>
            <div class="questions">
                <label>5) How useful of this system?<span class="required"> *</span></label>
                <input type="hidden" name="rating" id="rating_val" value="0" >
                Poor
                <span class="rating_stars rating_0" >
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
                Excellent
            </div>
            <div class="questions">
                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment" rows="7" cols="60" placeholder="Type something... (Max 500 words)"></textarea>
                <p id="wordCount">Words left: 500</p>
            </div>


        </div>
        <input type="submit">
    </form>


    @else
    <p class="mt-3"><a href="{{ route('login') }}">Login</a> to provide feedback.</p>
    @endauth

</div>







@endsection
@section('scripts')
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
});
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








