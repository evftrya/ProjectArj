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
            'id_product'=>$this->faker->numberBetween(1,20),
            'isMain'=>
        ];
    }

    public function photos(){
        $array = [
            "https://i.pinimg.com/236x/96/63/eb/9663ebfcd820b02d2205d21c03b22945.jpg",
            "https://i.pinimg.com/236x/72/98/98/7298982c05737fc4c601a2f8e5ee9323.jpg",
            "https://i.pinimg.com/236x/ef/0a/82/ef0a8228006cfda22a19f5d827b730fd.jpg",
            "https://i.pinimg.com/236x/ea/bc/bb/eabcbb61c6d185aa3d924caa43b5830f.jpg"



        ];

        return($array);
    }
}
