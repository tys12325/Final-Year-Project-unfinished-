@extends('layout.app')

@section('content')
    <h2>Two-Factor Authentication Challenge</h2>

    <form method="POST" action="{{ url('/two-factor-challenge') }}">
        @csrf
        <label>Enter Authentication Code:</label>
        <input type="text" name="code" required>

        <button type="submit">Verify</button>
    </form>

    <form method="POST" action="{{ url('/two-factor-challenge') }}">
        @csrf
        <label>Or use a recovery code:</label>
        <input type="text" name="recovery_code" required>

        <button type="submit">Verify</button>
    </form>
@endsection
