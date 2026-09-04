<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationOtpMail;

class RegisterOtpTest extends TestCase
{
    private string $testEmail = 'test_otp_signup_unique@gmail.com';
    private string $testPhone = '9111222333';
    private string $testPhoneFormatted = '+919111222333';

    protected function setUp(): void
    {
        parent::setUp();
        // Clean up any test user if exists
        $user = User::withTrashed()->where('email', $this->testEmail)->orWhere('phone', $this->testPhoneFormatted)->first();
        if ($user) {
            \App\Models\UserProfile::where('user_id', $user->id)->delete();
            \App\Models\UserRole::where('user_id', $user->id)->delete();
            \App\Models\Wallet::where('user_id', $user->id)->delete();
            \App\Models\LoginHistory::where('user_id', $user->id)->delete();
            $user->forceDelete();
        }
        Cache::forget("register_otp_" . md5($this->testEmail));
    }

    protected function tearDown(): void
    {
        $user = User::withTrashed()->where('email', $this->testEmail)->orWhere('phone', $this->testPhoneFormatted)->first();
        if ($user) {
            \App\Models\UserProfile::where('user_id', $user->id)->delete();
            \App\Models\UserRole::where('user_id', $user->id)->delete();
            \App\Models\Wallet::where('user_id', $user->id)->delete();
            \App\Models\LoginHistory::where('user_id', $user->id)->delete();
            $user->forceDelete();
        }
        Cache::forget("register_otp_" . md5($this->testEmail));
        parent::tearDown();
    }

    public function test_can_request_registration_otp()
    {
        Mail::fake();

        $response = $this->postJson('/api/v1/auth/register/request-otp', [
            'first_name' => 'Aditya',
            'last_name' => 'Verma',
            'email' => $this->testEmail,
            'phone' => $this->testPhone,
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'tenant',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => ['email', 'expires_in']
        ]);

        Mail::assertSent(EmailVerificationOtpMail::class, function ($mail) {
            return $mail->hasTo($this->testEmail);
        });

        $cached = Cache::get("register_otp_" . md5($this->testEmail));
        $this->assertNotNull($cached);
        $this->assertEquals($this->testEmail, $cached['email']);
        $this->assertEquals('Aditya', $cached['first_name']);
        $this->assertNotEmpty($cached['otp']);
        $this->assertEquals(6, strlen($cached['otp']));
    }

    public function test_cannot_verify_with_wrong_otp()
    {
        Mail::fake();

        $this->postJson('/api/v1/auth/register/request-otp', [
            'first_name' => 'Aditya',
            'last_name' => 'Verma',
            'email' => $this->testEmail,
            'phone' => $this->testPhone,
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'tenant',
        ]);

        $response = $this->postJson('/api/v1/auth/register/verify-otp', [
            'email' => $this->testEmail,
            'otp' => '000000',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false
        ]);
    }

    public function test_can_verify_registration_otp_and_create_account()
    {
        Mail::fake();

        $this->postJson('/api/v1/auth/register/request-otp', [
            'first_name' => 'Aditya',
            'last_name' => 'Verma',
            'email' => $this->testEmail,
            'phone' => $this->testPhone,
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'tenant',
        ]);

        $cached = Cache::get("register_otp_" . md5($this->testEmail));
        $this->assertNotNull($cached);
        $otp = $cached['otp'];

        $response = $this->postJson('/api/v1/auth/register/verify-otp', [
            'email' => $this->testEmail,
            'otp' => $otp,
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user' => [
                    'id',
                    'email',
                    'phone',
                    'first_name',
                    'last_name',
                    'role',
                    'status',
                ],
                'token',
                'token_type',
            ]
        ]);

        // User should now exist in DB
        $user = User::where('email', $this->testEmail)->first();
        $this->assertNotNull($user);
        $this->assertEquals($this->testPhoneFormatted, $user->phone);

        // Cache should be cleared
        $this->assertNull(Cache::get("register_otp_" . md5($this->testEmail)));
    }
}
