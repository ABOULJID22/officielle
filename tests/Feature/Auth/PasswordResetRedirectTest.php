<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Tests\TestCase;

class PasswordResetRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_password_reset_redirects_to_login()
    {
        // Create a user
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);

        // Create a token for the user
        $token = Password::createToken($user);

        // Post the reset form
        $response = $this->post(route('password.store'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        // Assert redirect to login
        $response->assertRedirect(route('login'));

        // Optionally assert the password was changed
        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }
}
