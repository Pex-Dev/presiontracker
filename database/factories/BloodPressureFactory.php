<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BloodPressure>
 */
class BloodPressureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'systolic' => $this->faker->numberBetween(50, 260),
            'diastolic' => $this->faker->numberBetween(30, 160),
            'pulse' => $this->faker->randomElement([null, $this->faker->numberBetween(30, 200)]),
            'temperature' => $this->faker->randomElement([null, $this->faker->numberBetween(35, 42)]),
            'measured_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'notes' => $this->faker->randomElement([$this->faker->sentence(7), null])
        ];
    }
}
