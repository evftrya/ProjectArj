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
            "1ORrDK2ZBGxyTVRj4gy8uoDwHC2wjjSQOKFZDAhQ.png",
            "Ag0aHLU3Zy6OZpobGxy16FEfKMorRSf2ILABC8P1.png",



        ];

        return($array);
    }
}
