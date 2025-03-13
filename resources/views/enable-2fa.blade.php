<div class="setting-content">
    <span>Two-Factor Authentication</span>
    <form method="POST" action="{{ auth()->user()->two_factor_secret ? route('2fa.disable') : route('2fa.enable') }}">
        @csrf
        @method(auth()->user()->two_factor_secret ? 'DELETE' : 'POST')
        <button type="submit">
            {{ auth()->user()->two_factor_secret ? 'Disable 2FA' : 'Enable 2FA' }}
        </button>
    </form>

    @if(auth()->user()->two_factor_secret)
        <p>Scan this QR code with Google Authenticator:</p>
        <img src="{{ auth()->user()->twoFactorQrCodeSvg() }}" alt="2FA QR Code">
        <p>Recovery Codes:</p>
        <ul>
            @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                <li>{{ $code }}</li>
            @endforeach
        </ul>
    @endif
</div>
