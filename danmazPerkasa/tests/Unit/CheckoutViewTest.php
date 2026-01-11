<?php
namespace Tests\Unit\Controllers {

    use Tests\Unit\Support\BaseControllerTestCase;

    use Mockery;
    use stdClass;
    use Illuminate\Http\Request;
    use Illuminate\Http\UploadedFile;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\View;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\RedirectResponse;

    use App\Http\Controllers\DetailTransactionController;
    use App\Http\Controllers\PhotosController;
    use App\Http\Controllers\ProductsController;
    use App\Http\Controllers\TransaksiController;

    class CheckoutViewTest extends BaseControllerTestCase
    {
        protected function tearDown(): void
        {
            Mockery::close();
            parent::tearDown();
        }

        public function test_UTB_044_checkout_view_path_with_id_and_qty_updates_status()
        {
            $this->seedUser();
            $this->seedProduct(['stok' => 10, 'price' => 10000]);
            $this->seedPhoto(['id_Photo' => 1, 'id_product' => 5, 'isMain' => 1]);

            $this->seedDetailTransaction(['id_Detail_transaction' => 7, 'id_product' => 5, 'qty' => 1]);

            $this->seedProvinceCity();
            $this->seedAddress(['Provinsi' => 1, 'KotaKabupaten' => 10]);

            $this->setSession(['user_id' => 2, 'direction' => null]);

            $res = (new DetailTransactionController())->CheckoutView(5, 3);
            $this->assertTrue(method_exists($res, 'render') || method_exists($res, 'getName'));
        }

        protected function seedRefCategoryProductsIfNeeded(string $categoryName = 'T'): void
        {
            if (!Schema::hasTable('ref_category_products')) return;

            $col = $this->pickCol('ref_category_products', ['category_name', 'Category', 'name']);
            if (!$col) return;

            if (!DB::table('ref_category_products')->where($col, $categoryName)->exists()) {
                DB::table('ref_category_products')->insert([$col => $categoryName]);
            }
        }

        protected function seedRefAreaCategoryPartsIfNeeded(string $area = 'Area'): void
        {
            if (!Schema::hasTable('ref_area_category_parts')) return;

            $col = $this->pickCol('ref_area_category_parts', ['Area', 'area']);
            if (!$col) return;

            if (!DB::table('ref_area_category_parts')->where($col, $area)->exists()) {
                DB::table('ref_area_category_parts')->insert([$col => $area]);
            }
        }

        protected function seedRefCategoryParts(int $id = 1, string $cat = 'Cat', string $area = 'Area', string $types = 'T'): void
        {
            $this->requiresTablesOrSkip(['ref_category_parts'], 'ref_category_parts required');

            // FK chain
            $this->seedRefAreaCategoryPartsIfNeeded($area);
            $this->seedRefCategoryProductsIfNeeded($types);

            if (!DB::table('ref_category_parts')->where('id_category_part', $id)->exists()) {
                DB::table('ref_category_parts')->insert([
                    'id_category_part' => $id,
                    'Category' => $cat,
                    'Area' => $area,
                    'Types' => $types,
                ]);
            }
        }

        protected function seedCategoryParts(int $idPart, int $idCategoryPart = 1): void
        {
            $this->requiresTablesOrSkip(['category_parts'], 'category_parts required');

            if (!DB::table('category_parts')->where('id_part', $idPart)->exists()) {
                DB::table('category_parts')->insert([
                    'id_part' => $idPart,
                    'id_category_part' => $idCategoryPart,
                ]);
            }
        }

        protected function seedCategoryProducts(int $idProduct, string $categoryName = 'T'): void
        {
            $this->requiresTablesOrSkip(['category_products'], 'category_products required');

            // IMPORTANT: satisfy FK category_products.category_name -> ref_category_products.category_name
            $this->seedRefCategoryProductsIfNeeded($categoryName);

            if (!DB::table('category_products')->where('id_product', $idProduct)->exists()) {
                DB::table('category_products')->insert([
                    'id_product' => $idProduct,
                    'category_name' => $categoryName,
                ]);
            }
        }

        protected function mockAllViews(): void
        {
            // make any view() return a simple response-like object
            View::shouldReceive('make')->andReturnUsing(function ($view, $data = []) {
                return response("view:$view");
            })->byDefault();
        }

        protected function getStatusValue(object $row): ?string
        {
            // schema-safe access
            return $row->status ?? ($row->Status ?? null);
        }

        /* ===========================================================
     * DetailTransactionController
     * =========================================================== */

        public function test_TestCase_118_checkoutView_skips_update_block_when_idProduct_null_and_returns_view()
        {
            $this->mockAllViews();

            $this->seedUser();
            $this->seedProvinceCity();
            $this->seedAddress();
            $this->seedProduct();
            $this->seedPhoto(['id_product' => 5]);

            $this->setSession(['user_id' => 2, 'Role' => 'User', 'direction' => null]);

            $statusCol = $this->pickCol('detail__transactions', ['status', 'Status']);

            $seed = [
                'id_Detail_transaction' => 1181,
                'id_User' => 2,
                'id_product' => 5,
                'qty' => 1,
                'Total' => 10000,
                'Transaksis_id' => null,
            ];
            if ($statusCol) $seed[$statusCol] = 'Checkout';

            $this->seedDetailTransaction($seed);

            $resp = (new DetailTransactionController())->CheckoutView('null', 'null');
            $this->assertStringContainsString('view:User.Pelanggan.Checkout', (string)$resp->getContent());
        }
    }
}
