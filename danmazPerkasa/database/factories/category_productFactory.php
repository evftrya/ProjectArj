<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\category_product>
 */
class category_productFactory extends Factory
{
    protected $model = \App\Models\category_product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_product' => $this->faker->numerify('##########'),
            'category_name' => $this->faker->randomElement(['Bass','Guitar']),
        ];
    }
}
