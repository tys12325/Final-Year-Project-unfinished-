
@extends('layout.app')

@section('title', 'Email Verify')
@section('head')
    @vite(['resources/css/emailVerify.css'])

@endsection
@if(session('message'))
<div class="alert alert-message">
       {{ session('message') }}
</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">
       {{ session('error') }}
    </div>

@endif

@section('content')
<a href="{{ route('userSetting') }}" id="backButton"><i class="fas fa-chevron-left fa-2x"></i></a>

<div class="VerifyBody">
    
    <form action="{{ route('send.verification.email') }}" method="POST">
        <h2>Verify Your Email</h2>
    @csrf
    <input type="email" name="email" placeholder="Enter your email" required>
    <button type="submit">Send Verification Email</button>
</form>
</div>


@endsection
@section('scripts')

<script>
setTimeout(function() {
    const Message = document.querySelector('.alert-message');
    const errorMessage = document.querySelector('.alert-danger');

    if (Message) {
        Message.classList.add('fade-out');
    }
    if (errorMessage) {
        errorMessage.classList.add('fade-out');
    }
}, 4000);
</script>
@endsection








