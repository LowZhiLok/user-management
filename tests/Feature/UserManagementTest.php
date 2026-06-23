<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_index_page_shows_users(): void
    {
        $user = User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone_number' => '1234567890',
            'status' => 'active',
        ]);

        $response = $this->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Jane Doe');
        $response->assertSeeText('jane@example.com');
    }

    public function test_store_creates_user_and_redirects(): void
    {
        $response = $this->post(route('users.store'), [
            'name' => 'John Test',
            'email' => 'john@example.com',
            'phone_number' => '0987654321',
            'password' => 'secret123',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'John Test',
            'email' => 'john@example.com',
            'phone_number' => '0987654321',
            'status' => 'active',
        ]);
    }

    public function test_update_changes_user_data(): void
    {
        $user = User::factory()->create([
            'name' => 'Update Test',
            'email' => 'update@example.com',
            'phone_number' => '1112223333',
            'status' => 'inactive',
            'password' => 'password',
        ]);

        $response = $this->put(route('users.update', $user), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone_number' => '1112223333',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'status' => 'active',
        ]);
    }

    public function test_destroy_soft_deletes_user(): void
    {
        $user = User::factory()->create();

        $response = $this->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'));
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    public function test_restore_recovers_soft_deleted_user(): void
    {
        $user = User::factory()->create();
        $user->delete();

        $response = $this->post(route('users.restore', $user->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $user->id, 'deleted_at' => null]);
    }
}
