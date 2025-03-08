<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_fetch_all_roles()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Teacher']);
        Role::create(['name' => 'Student']);

        $response = $this->getJson('/api/roles');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_user_can_view_a_single_role()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $role = Role::factory()->create();

        $response = $this->getJson("/api/roles/{$role->id}");

        $response->assertStatus(200)
            ->assertJson(['id' => $role->id, 'name' => $role->name]);
    }

    public function test_user_cannot_view_non_existent_role()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $response = $this->getJson('/api/roles/9999');

        $response->assertStatus(404);
    }

    public function test_user_can_create_a_role()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $data = ['name' => 'Admin'];

        $response = $this->postJson('/api/roles', $data);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Role created!']);

        $this->assertDatabaseHas('roles', $data);
    }

    public function test_user_cannot_create_role_with_invalid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $response = $this->postJson('/api/roles', ['name' => '']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_user_can_update_a_role()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $role = Role::factory()->create();
        $newData = ['name' => 'Updated Role'];

        $response = $this->putJson("/api/roles/{$role->id}", $newData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Role updated successfully']);

        $this->assertDatabaseHas('roles', $newData);
    }

    public function test_user_cannot_update_non_existent_role()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $response = $this->putJson('/api/roles/9999', ['name' => 'Updated Role']);

        $response->assertStatus(404);
    }

    public function test_user_cannot_update_role_with_invalid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $role = Role::factory()->create();

        $response = $this->putJson("/api/roles/{$role->id}", ['name' => '']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_user_can_delete_a_role()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $role = Role::factory()->create();

        $response = $this->deleteJson("/api/roles/{$role->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Role deleted successfully']);

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    public function test_user_cannot_delete_non_existent_role()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $response = $this->deleteJson('/api/roles/9999');

        $response->assertStatus(404);
    }
}
