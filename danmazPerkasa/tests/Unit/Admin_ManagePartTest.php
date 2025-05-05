<?php

namespace Tests\Unit;
use app\Models\Products;
use Tests\TestCase;

class Admin_ManagePartTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_AddPart_Success(): void
    {
        $price = fake()->numberBetween(500000, 100000000);
        $oriprice = 0.8*($price);
        $response = $this->post('/add-product/Part',[
            'ProductName' => fake()->words(6, true),
            'stock' => fake()->numberBetween(1, 9) * 100,
            'ProductPrice' => $price,
            'originalPrice' =>$oriprice,
            'ProductColor' => 'Blue',
            'shortQuotes' => fake()->words(20, true),
            'Description' => fake()->words(50, true),
            'weight' => fake()->numberBetween(1, 9) * 1000,
            'product' => fake()->randomElement(["Electric Guitar","Multi-scale"]),
        ]);

        $response->assertRedirect(('/Manage/Product/Part'));
    }

    public function test_EditPart_Success(): void{
        $price = fake()->numberBetween(500000, 100000000);
        $oriprice = 0.8*($price);
        $response = $this->post('/editPart/1',[
            'mainPhoto' => 'dIZsYS98TQx00JPIgVqTyAjXpitboHkb7YGDyJAX.png',
            'ProductName' => fake()->words(6, true),
            'stock' => fake()->numberBetween(1, 9) * 100,
            'ProductPrice' => $price,
            'originalPrice' =>$oriprice,
            'ProductColor' => fake()->randomElement([
                'Red', 'Blue', 'Yellow', 'Green', 'Orange', 'Purple', 'Black', 'White', 
                'Gray', 'Pink', 'Brown', 'Cyan', 'Magenta', 'Lime', 'Indigo', 'Violet', 
                'Turquoise', 'Gold', 'Silver', 'Maroon'
            ]),
            'shortQuotes' => fake()->words(20, true),
            'Description' => fake()->words(50, true),
            'weight' => fake()->numberBetween(1, 9) * 1000,
            'product' => fake()->randomElement(["Bass","Guitar"]),
        ]);

        $response->assertRedirect(('/Manage/Product/Part'));

    }

    public function test_deletePart_success(): void{
        $products = Products::factory(1)->create();
        // dd($products->first()->id_product);

        $response = $this->get('/deletePart/'.$products->first()->id_product);
        $response->assertRedirect(('/Manage/Product/Part'));
        $response->assertSessionHas('pesan');
    }
}
