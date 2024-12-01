<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'nama_product' =>$this->fakeword(6),
            'id_Detiltransaction'=>$this->faker->randomNumber(3),
            'detail_product'=>$this->fakeword(50),
            'type'=>$this->faker->randomElement(["Bass","Guitar"]),
        ];
    }

    public function fakeword($whtMuch){
        $sentence = implode(" ",$this->faker->words($whtMuch));
        return $sentence;
    }
}
