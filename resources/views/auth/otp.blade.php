@extends('layout.app')

@section('title', 'Phone Verification')

@section('head')
    @vite(['resources/css/phoneVerify.css'])
@endsection
@if(session('message'))
        <div class="alert alert-message">
            {{ session('message') }} <br>
            <a href="{{ route('verifyotp') }}" class="verify-link">Verify Here</a>
        </div>
        
@endif
@if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
@section('content')

<a href="{{route('userSetting')}}" id="backButton"><i class="fas fa-chevron-left fa-2x"></i></a>

<div class="verify-container">   
    <h2>Verify Phone Number</h2>
    <form class="phoneForm" action="{{ route('send-otp') }}" method="POST">
        @csrf
        <input type="text" name="phone" id="phoneNum" placeholder="Enter your phone number..." required>
        <input type="submit" class="sendOtp" value="Send OTP">
    </form>   
</div>

@endsection

@section('scripts')
<script>

setTimeout(function() {
    const errorMessage = document.querySelector('.alert-danger');

 
    if (errorMessage) {
        errorMessage.classList.add('fade-out');
    }
}, 4000);
</script>
@endsection
