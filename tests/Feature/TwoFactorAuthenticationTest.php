<?php


namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_two_factor_authentication_setup()
    {
        // Resolve the TwoFactorAuthenticationProvider from the container
        $provider = app(TwoFactorAuthenticationProvider::class);
        
        // Create a test user
        $user = User::factory()->create();

        // Enable 2FA for the user
        $enable2FA = new EnableTwoFactorAuthentication($provider);
        $enable2FA($user);

        // Get the 2FA secret
        $twoFactorSecret = decrypt($user->two_factor_secret); // Decrypt the secret before use

        // Simulate scanning the QR code and generating a TOTP code
        $google2fa = new Google2FA();
        $totpCode = $google2fa->getCurrentOtp($twoFactorSecret);

        // Confirm the 2FA setup
        $confirm2FA = new ConfirmTwoFactorAuthentication($provider);
        $confirm2FA($user, $totpCode);

        // Verify the 2FA setup
               $this->assertNotNull($user->fresh()->two_factor_confirmed_at);
    }
}
