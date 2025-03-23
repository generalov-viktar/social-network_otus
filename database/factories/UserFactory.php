<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstNameMale(),
            'second_name' => fake()->lastName(),
            'password' => Hash::make('password'),
            'birthdate' => fake()->dateTimeBetween('-60 years', '-18 years'),
            'biography' => fake()->realText(200),
            'city' => fake()->randomElement([
                'Москва',
                'Санкт-Петербург',
                'Новосибирск',
                'Екатеринбург',
                'Казань',
                'Нижний Новгород',
                'Челябинск',
                'Самара',
                'Уфа',
                'Ростов-на-Дону'
            ]),
        ];
    }
}
