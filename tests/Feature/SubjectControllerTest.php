<?php

namespace Tests\Feature;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectControllerTest extends TestCase
{
    use RefreshDatabase; // Resets the DB after each test

    protected function authenticate(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum'); // Assuming API uses Sanctum auth
    }

    /** @test */
    public function it_can_list_subjects()
    {
        $this->authenticate();
        Subject::factory()->count(3)->create();
        $response = $this->getJson('/api/subjects');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links']);
    }

    /** @test */
    public function it_can_search_for_subjects()
    {
        $this->authenticate();
        Subject::factory()->create(['name' => 'Mathematics']);
        Subject::factory()->create(['name' => 'History']);

        $response = $this->getJson('/api/subjects?search=Math');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Mathematics'])
            ->assertJsonMissing(['name' => 'History']);
    }
    /** @test */
    public function it_can_sort_subjects_by_created_at()
    {
        $this->authenticate();
        $oldest = Subject::factory()->create(['created_at' => now()->subDays(5)]);
        $latest = Subject::factory()->create(['created_at' => now()]);

        $response = $this->getJson('/api/subjects?sort_field=created_at&sort_order=asc');

        $response->assertStatus(200);
        $this->assertEquals($oldest->id, $response->json('data')[0]['id']);
    }

    /** @test */
    public function it_can_create_a_subject()
    {
        $this->authenticate();
        $response = $this->postJson('/api/subjects',[
            'name' => 'Mathematics',
        ]);
        $response->assertStatus(200)
            ->assertJson(['message' => 'Subject created successfully']);

        $this->assertDatabaseHas('subjects', ['name' => 'Mathematics']);
    }

    /** @test */
    public function it_fails_to_create_a_subject_with_invalid_data()
    {
        $this->authenticate();

        $response = $this->postJson('/api/subjects', ['name' => '']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_can_update_a_subject()
    {
        $this->authenticate();

        $subject = Subject::factory()->create();
        $updatedData = ['name' => 'Updated Subject'];

        $response = $this->putJson("/api/subjects/{$subject->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Subject Updated']);

        $this->assertDatabaseHas('subjects', $updatedData);
    }

    /** @test */
    public function it_fails_to_update_a_subject_with_invalid_data()
    {
        $this->authenticate();

        $subject = Subject::factory()->create();

        $response = $this->putJson("/api/subjects/{$subject->id}", ['name' => '']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_can_delete_a_subject()
    {
        $this->authenticate();

        $subject = Subject::factory()->create();

        $response = $this->deleteJson("/api/subjects/{$subject->id}");

        $response->assertStatus(200)
            ->assertJson(['message' =>'Subject deleted successfully']);

        $this->assertDatabaseMissing('subjects', ['id' => $subject->id]);
    }
}
