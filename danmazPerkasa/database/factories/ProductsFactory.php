<?php

namespace Database\Factories;

use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use faker\Factory as fakerFactory;
use App\Models\Photos;
// use App\Models\;

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
        $faker = fakerFactory::create('en_US');
        return [
            'nama_product' =>$faker->words(6, true),
            // 'id_Detiltransaction'=>$faker->randomNumber(3),
            'detail_product'=>$faker->words(50, true),
            'Features'=>$faker->words(50, true),
            'shortQuotes'=>$faker->words(20, true),
            'type'=>"Product",
            'isSpecial'=>"NEW",
            // 'weight'=>$ths->$faker->random
            'weight' => $faker->numberBetween(1, 9) * 1000,
            'stok' => $faker->numberBetween(1, 9) * 100,
            'price' => $faker->numberBetween(500000, 100000000),
            'originalPrice' => function($attributes) {
                return (int) round($attributes['price'] * 0.8);
            },
            // 'Category'=>$faker->randomElement(["Bass","Guitar"]),
            // 'color'=>$faker->randomElement(["Red","Blue","Yellow"]),
            'color' => $faker->randomElement([
                'Red', 'Blue', 'Yellow', 'Green', 'Orange', 'Purple', 'Black', 'White', 
                'Gray', 'Pink', 'Brown', 'Cyan', 'Magenta', 'Lime', 'Indigo', 'Violet', 
                'Turquoise', 'Gold', 'Silver', 'Maroon'
            ]),
            
        ];
    }
    
    public function fakeword($whtMuch){
        $faker = fakerFactory::create('en_US'); // << ini yang penting
        $sentence = implode(" ",$faker->words($whtMuch));
        return $sentence;
    }
}
