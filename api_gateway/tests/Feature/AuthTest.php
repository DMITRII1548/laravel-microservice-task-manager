<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_name_attibute_is_required_for_register_a_new_user(): void
    {
        $response = $this->post(route('auth.register'), [
            'email' => fake()->email(),
            'password' => '12345678',
        ]);

        $response->assertStatus(302);
    }

    public function test_name_attibute_is_string_for_register_a_new_user(): void
    {
        $response = $this->post(route('auth.register'), [
            'name' => 111,
            'email' => fake()->email(),
            'password' => '12345678',
        ]);

        $response->assertStatus(302);
    }

    public function test_name_attibute_is_min_two_symbols_for_register_a_new_user(): void
    {
        $response = $this->post(route('auth.register'), [
            'name' => 'a',
            'email' => fake()->email(),
            'password' => '12345678',
        ]);

        $response->assertStatus(302);
    }

    public function test_email_attibute_is_required_for_register_a_new_user(): void
    {
        $response = $this->post(route('auth.register'), [
            'name' => fake()->word(),
            // 'email' => fake()->email(),
            'password' => '12345678',
        ]);

        $response->assertStatus(302);
    }

    public function test_email_attibute_is_string_for_register_a_new_user(): void
    {
        $response = $this->post(route('auth.register'), [
            'name' => fake()->word(),
            'email' => 111,
            'password' => '12345678',
        ]);

        $response->assertStatus(302);
    }

    public function test_email_attibute_is_email_for_register_a_new_user(): void
    {
        $response = $this->post(route('auth.register'), [
            'name' => fake()->word(),
            'email' => fake()->word(),
            'password' => '12345678',
        ]);

        $response->assertStatus(302);
    }

    public function test_email_attibute_is_unique_to_database_for_register_a_new_user(): void
    {
        $user = User::factory()->create()->first();

        $response = $this->post(route('auth.register'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $response->assertStatus(302);
    }

    public function test_password_attibute_is_required_for_register_a_new_user(): void
    {
        $response = $this->post(route('auth.register'), [
            'name' => fake()->word(),
            'email' => fake()->email(),
        ]);

        $response->assertStatus(302);
    }

    public function test_password_attibute_is_min_8_sybmols_for_register_a_new_user(): void
    {
        $response = $this->post(route('auth.register'), [
            'name' => fake()->word(),
            'email' => fake()->email(),
            'password' => '1234567',
        ]);

        $response->assertStatus(302);
    }

    public function test_password_attibute_is_string_for_register_a_new_user(): void
    {
        $response = $this->post(route('auth.register'), [
            'name' => fake()->word(),
            'email' => fake()->email(),
            'password' => 12345678,
        ]);

        $response->assertStatus(302);
    }

    public function test_register_a_new_user(): void
    {
        $this->withExceptionHandling();

        $user = User::factory()->make();

        $response = $this->post(route('auth.register'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'response_code',
                'status',
                'message',
            ])
            ->assertJsonPath('response_code', '200')
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'success Register');

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_email_attribute_is_required_for_login(): void
    {
        Artisan::call('passport:client --personal');
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'password' => 'password',
        ]);

        $response->assertStatus(302);
    }

    public function test_email_attribute_is_string_for_login(): void
    {
        Artisan::call('passport:client --personal');
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => 1111,
            'password' => 'password',
        ]);

        $response->assertStatus(302);
    }

    public function test_email_attribute_is_email_for_login(): void
    {
        Artisan::call('passport:client --personal');
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => 'not email',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
    }

    public function test_password_attribute_is_required_for_login(): void
    {
        Artisan::call('passport:client --personal');
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user['email'],
            // 'password' => 'password',
        ]);

        $response->assertStatus(302);
    }

    public function test_password_attribute_is_string_for_login(): void
    {
        Artisan::call('passport:client --personal');
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user['email'],
            'password' => 111,
        ]);

        $response->assertStatus(302);
    }

    public function test_password_attribute_is_min_8_symbols_for_login(): void
    {
        Artisan::call('passport:client --personal');
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user['email'],
            'password' => 'passwor',
        ]);

        $response->assertStatus(302);
    }

    public function test_verify_password_for_login(): void
    {
        Artisan::call('passport:client --personal');
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user['email'],
            'password' => 'password12',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'response_code' => '401',
                'status' => 'error',
                'message' => 'Unauthorised',
            ]);
    }

    public function test_verify_email_for_login(): void
    {
        Artisan::call('passport:client --personal');
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user['email'].'1',
            'password' => 'password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'response_code' => '401',
                'status' => 'error',
                'message' => 'Unauthorised',
            ]);
    }

    public function test_verify_email_and_password_for_login(): void
    {
        Artisan::call('passport:client --personal');
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user['email'].'1',
            'password' => 'password1',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'response_code' => '401',
                'status' => 'error',
                'message' => 'Unauthorised',
            ]);
    }

    public function test_must_be_created_personal_token_for_login(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user['email'],
            'password' => 'password',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'response_code' => '401',
                'status' => 'error',
                'message' => 'Failed login',
            ]);
    }

    public function test_login_user(): void
    {
        Artisan::call('passport:client --personal');
        $user = User::factory()->create();

        $response = $this->post(route('auth.login'), [
            'email' => $user['email'],
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('response_code', '200')
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'success Login')
            ->assertJsonStructure([
                'response_code',
                'status',
                'message',
                'token',
            ]);
    }
}
