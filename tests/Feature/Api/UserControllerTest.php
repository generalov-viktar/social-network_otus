<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Random\RandomException;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $userData = [
            'first_name' => 'Иван',
            'second_name' => 'Иванов',
            'password' => 'password123',
            'birthdate' => '1990-01-01',
            'biography' => 'Разработчик',
            'city' => 'Москва',
        ];

        $response = $this->postJson('/api/user/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user_id',
            ]);

        $this->assertDatabaseHas('users', [
            'first_name' => $userData['first_name'],
            'second_name' => $userData['second_name'],
            'birthdate' => $userData['birthdate'],
            'biography' => $userData['biography'],
            'city' => $userData['city'],
        ]);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'id' => (string) $user->id,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
            ]);
    }

    /**
     * @throws RandomException
     */
    public function test_user_can_get_profile(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token->token
        ])->getJson("/api/user/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'first_name',
                'second_name',
                'birthdate',
                'biography',
                'city',
            ]);
    }

    public function test_returns_401_for_unauthorized_profile_access(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/user/{$user->id}");

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Неавторизованный доступ',
            ]);
    }

    /**
     * @throws RandomException
     */
    public function test_returns_404_for_non_existent_user(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token->token
        ])->getJson('/api/user/999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Пользователь не найден',
            ]);
    }
} 