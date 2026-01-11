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

    class TransactionUpdateTest extends BaseControllerTestCase
    {
        protected function tearDown(): void
        {
            Mockery::close();
            parent::tearDown();
        }

        /* ===========================================================
     * Extra schema-safe seeds for FK-heavy tables
     * =========================================================== */

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

        public function test_TestCase_136_transactionUpdate_minus_branch_reduces_stock()
        {
            $this->requiresTablesOrSkip(['detail__transactions', 'transaksis', 'products'], 'tables required');

            $this->seedUser();
            $this->seedProduct(['id_product' => 2001, 'stok' => 10, 'type' => 'Product']);
            $this->seedTransaksi(['id' => 2001, 'Status_Transaksi' => 'Acceptted']);

            $statusCol = $this->pickCol('detail__transactions', ['status', 'Status']);

            $row = [
                'id_Detail_transaction' => 20011,
                'id_User' => 2,
                'id_product' => 2001,
                'qty' => 2,
                'Total' => 20000,
                'Transaksis_id' => 2001,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if ($statusCol) $row[$statusCol] = 'Done';

            DB::table('detail__transactions')->insert($row);

            (new ProductsController())->TransactionUpdate(2001, 'minus');

            $p = DB::table('products')->where('id_product', 2001)->first();
            $this->assertSame(8, (int)$p->stok);
        }
    }
}