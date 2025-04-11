<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Products;
use App\Models\Photos;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Products::factory(30)->create();
        // Products::factory(10)->create()->each(function ($product) {
        //     $product->save();
        //     $photos = Photos::factory()->count(3)->create([
        //         'id_product' => $product->id
        //     ]);
    
        //     $mainPhoto = $photos->random();
        //     $product->mainPhoto = $mainPhoto->id;
        //     $product->save();
        // });

        foreach($products as $product){
            $photos = Photos::factory()->count(3)->create([
                'id_product' => $product->id_product
            ]);
            $photos[0]->isMain = 1;
            $photos[0]->save();
            // dd($photos[0]);
            $mainPhoto = $photos[0]->id_photo;
            // dd($mainPhoto);
            // 'mainPhoto' => $mainPhoto;
            $product->mainPhoto = $mainPhoto;
            // $product->forceFill([
            //     'mainPhoto' => $mainPhoto
            // ])->save();
            $product->save();
        }

        $Parts = Products::where('type', 'Part')->get();
        // dd($Parts);
        foreach($Parts as $Part){
            $photos = Photos::factory()->count(1)->create([
                'id_product' => $Part->id_product
            ]);
            $photos[0]->isMain = 1;
            $photos[0]->save();
            // dd($photos[0]);
            $mainPhoto = $photos[0]->id_photo;
            // dd($mainPhoto);
            // 'mainPhoto' => $mainPhoto;
            $Part->mainPhoto = $mainPhoto;
            // $product->forceFill([
            //     'mainPhoto' => $mainPhoto
            // ])->save();
            $Part->save();
        }

        // $product = Products::factory()->make();
        // $product->save();

        // $photos =Photos::factory()->count(1)->for($product)->create();
        // $mainPhoto = $photos->random();

        // $product->mainPhoto = $mainPhoto->id;
        // $product->save();
        // Products::factory(10)->create();
    }
}
