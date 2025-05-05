<?php

namespace Tests\Unit;
use app\Models\Products;
use Tests\TestCase;

class Admin_ManageProductTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_AddProduct_Success(): void
    {
        $price = fake()->numberBetween(500000, 100000000);
        $oriprice = 0.8*($price);
        $response = $this->post('/add-product/Product',[
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

        $response->assertRedirect(('/Manage/Product/Product'));
    }

    public function test_EditProduct_Success(): void{
        $price = fake()->numberBetween(500000, 100000000);
        $oriprice = 0.8*($price);
        $response = $this->post('/editProduct/216',[
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

        $response->assertRedirect(('/Manage/Product/Product'));

    }

    public function test_deleteProduct_success(): void{
        $products = Products::factory(1)->create();
        // dd($products->first()->id_product);

        $response = $this->get('/deleteProduct/'.$products->first()->id_product);
        $response->assertRedirect(('/Manage/Product/Product'));
        $response->assertSessionHas('pesan');
    }
}
