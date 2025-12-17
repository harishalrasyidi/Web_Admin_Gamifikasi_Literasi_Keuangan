<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Player;
use Laravel\Sanctum\Sanctum;

class CharacterSelectTest extends TestCase
{
    use RefreshDatabase;

    public function test_select_character_with_snake_case()
    {
        // Setup
        $player = Player::factory()->create();
        $user = User::factory()->create();
        $user->player()->save($player);

        Sanctum::actingAs($user);

        // Act
        $response = $this->postJson('/api/matchmaking/character/select', [
            'character_id' => 1
        ]);

        // Assert
        $response->assertStatus(200)
            ->assertJson(['ok' => true]);

        $this->assertDatabaseHas('players', [
            'PlayerId' => $player->PlayerId,
            'character_id' => 1
        ]);
    }

    public function test_select_character_with_camel_case()
    {
        // Setup
        $player = Player::factory()->create();
        $user = User::factory()->create();
        $user->player()->save($player);

        Sanctum::actingAs($user);

        // Act
        $response = $this->postJson('/api/matchmaking/character/select', [
            'characterId' => 2
        ]);

        // Assert
        $response->assertStatus(200)
            ->assertJson(['ok' => true]);

        $this->assertDatabaseHas('players', [
            'PlayerId' => $player->PlayerId,
            'character_id' => 2
        ]);
    }
}
