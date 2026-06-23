<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_list_users(): void
    {
        User::factory(3)->create(['status' => 'active']);
        User::factory(2)->create(['status' => 'inactive']);

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 5)
                ->has('meta')
                ->has('links')
        );
    }

    public function test_api_list_users_filtered_by_status(): void
    {
        User::factory(3)->create(['status' => 'active']);
        User::factory(2)->create(['status' => 'inactive']);

        $response = $this->getJson('/api/v1/users?status=active');

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 3)
                ->has('meta')
                ->has('links')
        );
    }

    public function test_api_list_users_filtered_by_name(): void
    {
        User::factory()->create(['name' => 'Alice Search']);
        User::factory()->create(['name' => 'Bob Search']);

        $response = $this->getJson('/api/v1/users?name=Alice');

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 1)
                ->where('data.0.name', 'Alice Search')
                ->has('meta')
                ->has('links')
        );
    }

    public function test_api_create_user(): void
    {
        $response = $this->postJson('/api/v1/users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone_number' => '1234567890',
            'password' => 'password123',
            'status' => 'active',
        ]);

        $response->assertStatus(201);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('data.name', 'Test User')
                ->where('data.email', 'test@example.com')
                ->where('data.status', 'active')
        );
    }

    public function test_api_show_user(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/v1/users/{$user->id}");

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('data.id', $user->id)
                ->where('data.name', $user->name)
                ->where('data.email', $user->email)
        );
    }

    public function test_api_update_user(): void
    {
        $user = User::factory()->create();

        $response = $this->putJson("/api/v1/users/{$user->id}", [
            'name' => 'Updated User',
            'status' => 'inactive',
        ]);

        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('data.name', 'Updated User')
                ->where('data.status', 'inactive')
        );
    }

    public function test_api_delete_user(): void
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/v1/users/{$user->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_api_bulk_delete_users(): void
    {
        $users = User::factory(3)->create();

        $response = $this->deleteJson('/api/v1/users', [
            'ids' => $users->pluck('id')->toArray(),
        ]);

        $response->assertStatus(200);
        $this->assertCount(0, User::whereNull('deleted_at')->get());
    }

    public function test_api_validation_errors(): void
    {
        $response = $this->postJson('/api/v1/users', [
            'name' => '',
            'email' => 'invalid-email',
            'phone_number' => '',
            'password' => 'short',
            'status' => 'unknown',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'phone_number', 'password', 'status']);
    }

    public function test_api_unique_email_validation(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->postJson('/api/v1/users', [
            'name' => 'Another User',
            'email' => 'existing@example.com',
            'phone_number' => '9999999999',
            'password' => 'password123',
            'status' => 'active',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_api_export_users(): void
    {
        User::factory(5)->create(['status' => 'active']);

        $response = $this->getJson('/api/v1/users-export?status=active');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertHeader('Content-Disposition');
    }
}
