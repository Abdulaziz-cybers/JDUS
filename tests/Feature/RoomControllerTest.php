<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoomControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_rooms_list()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        Room::factory()->count(3)->create();

        $response = $this->getJson('/api/rooms');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_user_can_view_a_single_room()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $room = Room::factory()->create();

        $response = $this->getJson("/api/rooms/{$room->id}");

        $response->assertStatus(200)
            ->assertJson(['id' => $room->id, 'name' => $room->name]);
    }

    public function test_user_can_create_a_room()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $roomData = ['name' => 'New Room', 'type' => 'conference'];

        $response = $this->postJson('/api/rooms', $roomData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Room created successfully']);

        $this->assertDatabaseHas('rooms', ['name' => 'New Room']);
    }

    public function test_user_can_update_a_room()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $room = Room::factory()->create();
        $updateData = ['name' => 'Updated Room'];

        $response = $this->putJson("/api/rooms/{$room->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Room updated successfully']);

        $this->assertDatabaseHas('rooms', ['id' => $room->id, 'name' => 'Updated Room']);
    }

    public function test_user_can_delete_a_room()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $room = Room::factory()->create();

        $response = $this->deleteJson("/api/rooms/{$room->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Room deleted successfully']);

        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
    }

    public function test_cannot_create_room_with_invalid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $response = $this->postJson('/api/rooms', [
            'name' => '', // Missing name
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_cannot_update_room_with_invalid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $room = Room::factory()->create();

        $response = $this->putJson("/api/rooms/{$room->id}", [
            'name' => '', // Invalid empty name
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_cannot_show_non_existent_room()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $response = $this->getJson('/api/rooms/9999');
        $response->assertStatus(404);
    }

    public function test_cannot_delete_non_existent_room()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $response = $this->deleteJson('/api/rooms/9999');
        $response->assertStatus(404);
    }

    public function test_cannot_sort_rooms_with_invalid_field()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        Room::factory()->count(3)->create();

        $response = $this->getJson('/api/rooms?sort_field=invalid_field');

        $response->assertStatus(500); // Assuming validation exists to prevent sorting by invalid fields
    }
}
