<?php

namespace Tests\Unit;

use App\Exports\UsersExport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_export_maps_expected_columns_and_filters_by_name(): void
    {
        User::factory()->create([
            'name' => 'Alice Export',
            'email' => 'alice@example.com',
            'phone_number' => '1234567890',
            'status' => 'active',
            'created_at' => '2026-06-23 10:00:00',
        ]);

        User::factory()->create([
            'name' => 'Bob Export',
            'email' => 'bob@example.com',
            'phone_number' => '1234567891',
            'status' => 'active',
            'created_at' => '2026-06-24 10:00:00',
        ]);

        $rows = (new UsersExport('active', 'Alice'))->collection();

        $this->assertCount(1, $rows);
        $this->assertSame([
            'id' => 1,
            'name' => 'Alice Export',
            'email' => 'alice@example.com',
            'phone_number' => '1234567890',
            'status' => 'active',
            'created_at' => '2026-06-23',
        ], $rows->first());
    }
}
