@extends('layout.app')

@section('title', 'FeedBack Support')
@section('head')
    @vite(['resources/css/feedback/FeedbackAndSupport.css'])
@endsection

@section('content')
    <a href="#" id="backButton"><i class="fas fa-chevron-left fa-2x"></i></a>
        <div class="container">
            <div class="header">Feedback and Support</div>
            <div class="icon-row">
                <div class="icon">
                    <a href="{{ route('FAQ') }}">
                        <i class="fas fa-question-circle"></i> 
                        FAQ
                    </a>
                </div>
                <div class="icon">
                    <a href="{{ route('chatbot') }}">
                        <i class="fas fa-headset"></i> 
                        Live Chat
                    </a>

                </div>
                <div class="icon">
                    <a href="{{ route('feedback') }}">
                        <i class="fas fa-comment-alt"></i> 
                        Feedback
                    </a>

                </div>
                <div class="icon">
                    <a href="{{ route('universities.indexes') }}">
                        <i class="fas fa-thumbs-up"></i> 
                        Rating
                    </a>

                </div>
            </div>
        </div>
@endsection
@section('scripts')

@endsection




  



