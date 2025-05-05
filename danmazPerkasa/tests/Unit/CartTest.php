<?php
namespace Tests\Unit;
use Tests\TestCase;
use App\Models\Products;
use App\Models\Photos;
use function PHPUnit\Framework\assertSame;

class CartTest extends TestCase
{
    /**
     * A basic unit test example.
     */

    public function test_Make_Cart_In_TheNewDetilTransaction()
    {
        //login
        $response = $this->get('/Logout');
        $response = $this->post('/loginAccount',[
            'emailUser'     => 'q@q',
            'passwordUser'  => '123',
        ]);
        $response->assertRedirect('/Login');

        $products = Products::factory(1)->state([
            'nama_product' => 'baruuuuu',
        ])->create();
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
        $response = $this->post('/AddToCart/'.$products[0]->id_product,[
            'qty' => 1,
        ]);
        $response->assertSee('successNew');
        return $products[0]->id_product;
    }
    public function test_Make_Cart_In_TheExistDetilTransaction(): void
    {

        $products = $this->test_Make_Cart_In_TheNewDetilTransaction();
        $response = $this->post('/AddToCart/'.$products,[
            'qty' => 1,
        ]);

        $response->assertSee('successOld');
        $response = $this->get('/Logout');
    }

    public function test_Make_Cart_In_TheEmptyStockOfProduct()
    {
        //login
        $response = $this->get('/Logout');
        $response = $this->post('/loginAccount',[
            'emailUser'     => 'q@q',
            'passwordUser'  => '123',
        ]);
        $response->assertRedirect('/Login');

        $products = Products::factory(1)->state([
            'stok' => 0,
        ])->create();

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
        $response = $this->post('/AddToCart/'.$products[0]->id_product,[
            'qty' => 1,
        ]);
        $response->assertSee('NoStock');
    }


    public function test_checklistToCheckout(){
        $response = $this->get('/Logout');
        $response = $this->post('/loginAccount',[
            'emailUser'     => 'q@q',
            'passwordUser'  => '123',
        ]);
        $response->assertRedirect('/Login');
        $Dt = $this->test_Make_Cart_In_TheNewDetilTransaction();
        $response = $this->get('/UpdateStatus/'.$Dt."/1"); 
        $response->assertJson(['message' => "success"]);
    }

    public function test_UnchecklistToCheckout(){
        $Dt = $this->test_Make_Cart_In_TheNewDetilTransaction();
        $response = $this->get('/UpdateStatus/'.$Dt."/2"); 
        $response->assertJson(['message' => "success"]);
    }

    public function test_setAddresss(){
        $response = $this->get('/Logout');
        $response = $this->post('/loginAccount',[
            'emailUser'     => 'q@q',
            'passwordUser'  => '123',
        ]);
        $response = $this->post('/Profile/Address-Update',[
            'RT'=>null,
            'RW'=>null,
            'provinsi' => '5',
            'KotaKabupaten'=>'135',
            'Kecamatan'=>null,
            'Kelurahan'=>null,
            'AlamatDetail'=>'Kabupaten Gunung Kidul, Prov. DI Yogyakarta, Indonesia',
        ]);
        $response->assertRedirect('/Profile/Address');
        $response->assertSessionHas('message', 'Address change succesfully');
        $response = $this->get('/Logout');
    }

    public function test_ExistAddress(){
        $response = $this->get('/Logout');
        $response = $this->post('/loginAccount',[
            'emailUser'     => 'q@q',
            'passwordUser'  => '123',
        ]);
        $response = $this->get('/isNew/CekAddress');
        $response->assertSee(1);
    }

    public function test_NonExistAddress(){
        $response = $this->get('/Logout');
        $response = $this->post('/loginAccount',[
            'emailUser'     => 'w@w',
            'passwordUser'  => '123',
        ]);
        $response = $this->get('/isNew/CekAddress');
        $response->assertSee(0);
    }
}
