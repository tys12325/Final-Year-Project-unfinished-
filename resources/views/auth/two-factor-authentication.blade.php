@extends('layout.app')

@section('content')
    <h2>Two-Factor Authentication</h2>

    @if (auth()->user()->two_factor_secret)
        <p>2FA is enabled.</p>

        <div>
            {!! auth()->user()->twoFactorQrCodeSvg() !!}
        </div>

        <h3>Recovery Codes</h3>
        <ul>
            @foreach (auth()->user()->recoveryCodes() as $code)
                <li>{{ $code }}</li>
            @endforeach
        </ul>

        <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
            @csrf
            @method('DELETE')
            <button type="submit">Disable 2FA</button>
        </form>
    @else
        <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
            @csrf
            <button type="submit">Enable 2FA</button>
        </form>
    @endif
@endsection
