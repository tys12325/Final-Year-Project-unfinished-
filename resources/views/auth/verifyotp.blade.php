
@extends('layout.app')

@section('title', 'Phone Verification')

@section('head')
    @vite(['resources/css/phoneVerify.css'])
@endsection

@section('content')



<div class="verify-container">

    @if(session('message'))
        <div class="alert alert-message" >{{ session('message') }}</div>
        
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h2>Verify Phone Number</h2>

     <form class="otpForm" action="{{ route('verify-otp') }}" method="POST">
        @csrf
        <div class="input-group">
            <input type="text" name="otp" id="otpCode" placeholder="Enter the OTP..." required >
        </div>
        <button type="submit" class="btn-submit" >Verify OTP</button>
    </form>
    <a href="{{route('userSetting')}}" id="backHomeNav">Back Home</a>

  
   
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

