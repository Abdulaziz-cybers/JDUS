<?php
namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_groups_list()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Group::factory()->count(3)->create();

        $response = $this->getJson('/api/groups');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_user_can_search_groups()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Group::factory()->create(['name' => 'Laravel Group']);
        Group::factory()->create(['name' => 'PHP Group']);

        $response = $this->getJson('/api/groups?search=Laravel');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Laravel Group'])
            ->assertJsonMissing(['name' => 'PHP Group']);
    }

    public function test_user_can_view_a_single_group()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $group = Group::factory()->create();

        $response = $this->getJson("/api/groups/{$group->id}");

        $response->assertStatus(200)
            ->assertJson(['id' => $group->id, 'name' => $group->name]);
    }

    public function test_user_cannot_view_a_non_existing_group()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/groups/99999');

        $response->assertStatus(404);
    }

    public function test_user_can_create_a_group()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/groups',[
            'name' => 'Laravel Group',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Group created successfully']);

        $this->assertDatabaseHas('groups', ['name' => 'Laravel Group']);
    }

    public function test_user_cannot_create_a_group_with_invalid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/groups', [
            'name' => ''
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_user_can_update_a_group()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $group = Group::factory()->create();

        $response = $this->putJson("/api/groups/{$group->id}",[
            'name' => 'Updated Group',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Group updated successfully']);

        $this->assertDatabaseHas('groups', ['id' => $group->id, 'name' => 'Updated Group']);
    }

    public function test_user_cannot_update_a_non_existing_group()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $response = $this->putJson('/api/groups/99999', ['name' => 'Updated Group']);

        $response->assertStatus(404);
    }

    public function test_user_cannot_update_a_group_with_invalid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $group = Group::factory()->create();

        $response = $this->putJson("/api/groups/{$group->id}", ['name' => '']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_user_can_delete_a_group()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $group = Group::factory()->create();

        $response = $this->deleteJson("/api/groups/{$group->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Group deleted successfully']);

        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    }

    public function test_user_cannot_delete_a_non_existing_group()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $response = $this->deleteJson('/api/groups/99999');

        $response->assertStatus(404);
    }
}
