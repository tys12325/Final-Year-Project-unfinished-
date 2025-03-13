@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Verify Your Email</h2>
        <p>Before you can access this site, please verify your email address.</p>
        
        @if (session('message'))
            <p class="alert alert-success">{{ session('message') }}</p>
        @endif

        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Resend Verification Email</button>
        </form>
    </div>
 <script>
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home')->with('message', 'Your email is already verified.');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'A new verification link has been sent to your email.');
    }    
    if (Auth::user()->hasVerifiedEmail()) {
    return redirect('/home')->with('message', 'Your email is already verified.');
}
 </script>
@endsection
