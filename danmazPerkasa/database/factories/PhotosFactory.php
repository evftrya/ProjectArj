<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Photos>
 */
class PhotosFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


     
    public function definition(): array
    {
        return [
            'PhotosName' =>$this->faker->randomElement($this->photos()),
            // 'id_product'=>$this->faker->numberBetween(1,20),
            'isMain'=>null,
            // 'isMain'=>
        ];
    }

    public function photos(){
        $array = [
            "403d0b1ed5bd58d1a65d8771cb44cbf7.jpg",
            "6ee38ef820613c856b4dc603bf2f1e2a.jpg",
            "bae38365aa9dca16fb5dbecc1640e554.jpg",



        ];

        return($array);
    }
}
