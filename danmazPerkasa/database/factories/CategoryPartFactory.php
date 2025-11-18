<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\category_part>
 */
class CategoryPartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_part' => $this->faker->numerify('##########'),
            'id_category_part' => $this->faker->numerify('##########'),
        ];
    }
}
