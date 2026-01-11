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

    class getDataTest extends BaseControllerTestCase
    {
        

        /* =========================
     * AccountController UTB
     * ========================= */

        public function test_UTB_056_products_getData_from_product_allproduct_returns_all()
        {
            $this->seedProduct(['id_product' => 5, 'stok' => 0, 'type' => 'Product', 'mainPhoto' => 1]);
            $this->seedPhoto(['id_Photo' => 1, 'id_product' => 5, 'isMain' => 1]);

            $this->seedProduct(['id_product' => 6, 'stok' => 10, 'type' => 'Product', 'mainPhoto' => 1]);

            $data = (new ProductsController())->getData('AllProduct', 'Product');
            $this->assertGreaterThanOrEqual(1, count($data));
        }

        public function test_UTB_057_products_get_data_non_manage_filters_stock_gt_0()
        {
            $this->requiresTablesOrSkip(
                ['products', 'photos', 'category_products', 'ref_category_products'],
                'Need products/photos/category_products/ref_category_products'
            );

            DB::table('ref_category_products')->insert([
                'category_name' => 'Cat',
            ]);

            $this->seedProduct(['id_product' => 5, 'stok' => 0, 'type' => 'Product', 'mainPhoto' => 1]);
            $this->seedPhoto(['id_Photo' => 1, 'id_product' => 5, 'isMain' => 1]);

            $this->seedProduct(['id_product' => 6, 'stok' => 10, 'type' => 'Product', 'mainPhoto' => 1]);

            DB::table('category_products')->insert([
                ['id_product' => 5, 'category_name' => 'Cat'],
                ['id_product' => 6, 'category_name' => 'Cat'],
            ]);

            $data = (new ProductsController())->getData(null, 'Product');
            foreach ($data as $row) {
                $this->assertTrue($row->stok > 0);
            }
        }

        public function test_UTB_058_products_getData_from_part_filters_type_part()
        {
            $this->seedProduct(['id_product' => 5, 'type' => 'Part', 'stok' => 10]);
            $this->seedProduct(['id_product' => 6, 'type' => 'Product', 'stok' => 10]);

            $data = (new ProductsController())->getData(null, 'Part');
            foreach ($data as $row) {
                $this->assertSame('Part', $row->type);
            }
        }

        public function test_TestCase_140_refresh_getDataRefresh_getDataNew_setNew_cover_flow()
        {
            $this->seedProduct(['id_product' => 4001, 'type' => 'Product', 'isSpecial' => null]);

            $pc = new ProductsController();

            $pc->setNew(4001);
            $this->assertSame('NEW', DB::table('products')->where('id_product', 4001)->value('isSpecial'));

            $new = $pc->getDataNew();
            $this->assertNotNull($new);

            $this->seedPhoto(['id_Photo' => 4001, 'id_product' => 4001]);
            DB::table('products')->where('id_product', 4001)->update(['mainPhoto' => 4001]);

            $ref = $pc->getDataRefresh();
            $this->assertNotNull($ref);

            $ref2 = $pc->refresh();
            $this->assertNotNull($ref2);
        }
    }
}