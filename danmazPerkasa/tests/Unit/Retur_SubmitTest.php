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
    use App\Http\Controllers\ReturnPesananController;
    use App\Http\Controllers\TransaksiController;

    class Retur_SubmitTest extends BaseControllerTestCase
    {
        protected function tearDown(): void
        {
            Mockery::close();
            parent::tearDown();
        }

        public function test_UTB_061_retur_submit_creates_and_redirects()
        {
            $this->requiresTablesOrSkip(['return_pesanans', 'detail__transactions', 'products'], 'Need return_pesanans/detail__transactions/products');

            $this->seedProduct(['id_product' => 5]);
            $this->seedUser();
            $this->seedDetailTransaction(['id_Detail_transaction' => 7, 'id_product' => 5]);

            $req = $this->makeRequest([
                'Barang' => 5,
                'id_detil_transaksi' => 7,
                'qty_retur' => 1,
            ], 'POST');

            $res = (new ReturnPesananController())->Retur_Submit($req);

            $this->assertStringContainsString('/Retur-Produk/Lengkapi-data/', $res->getTargetUrl());

            $this->assertDatabaseHas('return_pesanans', [
                'Barang' => 5,
                'id_detil_transaksi' => 7,
                'qty_retur' => 1,
            ]);
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

        public function test_TestCase_153_retur_submit_empty_method_returns_null()
        {
            $this->assertNull((new TransaksiController())->Retur_Submit());
        }
    }
}