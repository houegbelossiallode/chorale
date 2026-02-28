<?php

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;
use App\Services\SupabaseService;
use Tests\TestCase;
use Mockery\MockInterface;

class PasswordResetTest extends TestCase
{
    public function test_reset_password_request_screen_can_be_rendered(): void
    {
        $response = $this->get('/password/reset');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Mail::fake();

        $this->mock(SupabaseService::class, function (MockInterface $mock) {
            $mock->shouldReceive('createResetToken')
                ->with('test@example.com')
                ->once()
                ->andReturn('fake-token-123');
        });

        $response = $this->post('/password/email', ['email' => 'test@example.com']);

        $response->assertRedirect()
            ->assertSessionHas('status', 'Password reset link sent.');

        Mail::assertSent(PasswordResetMail::class, function ($mail) {
            return str_contains($mail->resetUrl, 'fake-token-123');
        });
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $response = $this->get('/password/reset/fake-token-123');

        $response->assertStatus(200)
            ->assertViewHas('token', 'fake-token-123');
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $this->mock(SupabaseService::class, function (MockInterface $mock) {
            $mock->shouldReceive('resetUserPassword')
                ->with('fake-token-123', 'new-password')
                ->once()
                ->andReturn(true);
        });

        $response = $this->post('/password/reset', [
            'token' => 'fake-token-123',
            'email' => 'test@example.com',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect(route('login'))
            ->assertSessionHas('status', 'Password has been reset.');
    }

    public function test_password_reset_fails_with_invalid_token(): void
    {
        $this->mock(SupabaseService::class, function (MockInterface $mock) {
            $mock->shouldReceive('resetUserPassword')
                ->with('invalid-token', 'new-password')
                ->once()
                ->andReturn(false);
        });

        $response = $this->post('/password/reset', [
            'token' => 'invalid-token',
            'email' => 'test@example.com',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect()
            ->assertSessionHas('error', 'Invalid or expired token.');
    }
}
