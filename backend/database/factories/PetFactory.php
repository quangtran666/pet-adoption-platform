<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $petTypes = ['Dog', 'Cat', 'Bird', 'Rabbit', 'Hamster', 'Fish', 'Turtle', 'Guinea Pig'];
        $statuses = ['pending_approval', 'active', 'adopted'];

        return [
            'user_id' => User::factory(),
            'name' => fake()->firstName(),
            'age' => fake()->randomElement(['Baby', '6 months', '1 year', '2 years', '3 years', 'Adult', 'Senior']),
            'type' => fake()->randomElement($petTypes),
            'description' => fake()->paragraph(3, true),
            'location' => fake()->city() . ', ' . fake()->country(),
            'status' => fake()->randomElement($statuses)
        ];
    }

    public function pendingApproval() : static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending_approval',
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function adopted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'adopted',
        ]);
    }
}
