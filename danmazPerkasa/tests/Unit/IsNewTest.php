<?php
namespace Tests\Feature {

     use Tests\Unit\Support\BaseControllerTestCase;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Http\Request;
    use Illuminate\Http\UploadedFile;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Support\Facades\Schema;

    // Controllers
    use App\Http\Controllers\AccountController;
    use App\Http\Controllers\AddressController;
    use App\Http\Controllers\Controller as BaseAppController;
    use App\Http\Controllers\DetailTransactionController;
    use App\Http\Controllers\NotificationController;
    use App\Http\Controllers\PhotosController;
    use App\Http\Controllers\ProductsController;
    use App\Http\Controllers\ReturnPesananController;
    use App\Http\Controllers\TransaksiController;

    class isNewTest extends BaseControllerTestCase
    {
        

        /* =========================
     * AccountController UTB
     * ========================= */

        public function test_UTB_025_isNew_returns_1_when_address_exists()
        {
            $this->requiresTablesOrSkip(['addresses', 'products'], 'Addresses & products tables are required');

            $this->seedUser();
            $this->seedAddress();
            $this->seedProduct(['id_product' => 5, 'stok' => 10]);
            $this->setSession(['user_id' => 2]);

            $res = (new AddressController())->isNew(5);
            $this->assertSame(1, json_decode($res->getContent(), true));
        }

        public function test_UTB_026_isNew_returns_1_when_idProduct_address()
        {
            $this->seedUser();
            $this->setSession(['user_id' => 2]);

            $res = (new AddressController())->isNew('Address');
            $this->assertSame(1, json_decode($res->getContent(), true));
        }

        public function test_UTB_027_isNew_returns_0_when_idProduct_cekAddress()
        {
            $this->seedUser();
            $this->setSession(['user_id' => 2]);

            $res = (new AddressController())->isNew('CekAddress');
            $this->assertSame(0, json_decode($res->getContent(), true));
        }

        public function test_UTB_028_isNew_returns_2_when_product_stock_zero()
        {
            $this->seedUser();
            $this->seedProduct(['stok' => 0]);
            $this->setSession(['user_id' => 2]);

            $res = (new AddressController())->isNew(5);
            $this->assertSame(2, json_decode($res->getContent(), true));
        }

        public function test_UTB_029_isNew_returns_0_default()
        {
            $this->seedUser();
            $this->seedProduct(['stok' => 10]);
            $this->setSession(['user_id' => 2]);

            $res = (new AddressController())->isNew(5);
            $this->assertSame(0, json_decode($res->getContent(), true));
        }
    }
}