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

    class viewTransactionTest extends BaseControllerTestCase
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

        public function test_TestCase_147_viewTransaction_returns_view_stub_when_idT_nonzero()
        {
            $this->mockAllViews();

            $this->requiresTablesOrSkip(['transaksis', 'detail__transactions', 'products', 'photos', 'users', 'addresses'], 'tables required');

            $this->seedAdmin();
            $this->seedUser();
            $this->setSession(['user_id' => 1, 'Role' => 'Admin']);

            // ====== IMPORTANT FIX: Seed address matching AddressController::getDetil() ======
            // AddressController uses Address::where('id_User', $idUser)->first()->Detil
            $addrUserCol = $this->pickCol('addresses', ['id_User', 'id_user']);
            $this->assertNotNull($addrUserCol, 'addresses table must have id_User or id_user');

            // ensure province/city exist if your addresses table requires them
            if (Schema::hasTable('provinces') && Schema::hasTable('cities')) {
                $this->seedProvinceCity();
            }

            // insert address row that AddressController can read
            $row = [
                $addrUserCol => 2, // transaction belongs to user 2
                'Detil' => 'Jl. A, Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // optional columns if exist (avoid SQL errors)
            foreach (
                [
                    'Provinsi' => 1,
                    'KotaKabupaten' => 10,
                    'Kecamatan' => 'Kec A',
                    'Kelurahan' => 'Kel A',
                    'RT' => '01',
                    'RW' => '02',
                    'KodePos' => '12345',
                    'AlamatDetil' => 'Jl. A',
                    'ShippingRate' => json_encode(['data' => []]),
                ] as $k => $v
            ) {
                if (Schema::hasColumn('addresses', $k) && !array_key_exists($k, $row)) {
                    $row[$k] = $v;
                }
            }

            // clean existing for user 2 to avoid duplicates
            DB::table('addresses')->where($addrUserCol, 2)->delete();
            DB::table('addresses')->insert($row);
            // ==============================================================================

            $this->seedProduct(['id_product' => 6001, 'type' => 'Product']);
            $this->seedPhoto(['id_Photo' => 6001, 'id_product' => 6001]);
            DB::table('products')->where('id_product', 6001)->update(['mainPhoto' => 6001]);

            $this->seedTransaksi([
                'id' => 6001,
                'id_user' => 2,
                'Status_Pembayaran' => 'Done',
                'Shipping' => json_encode(['JNE|REG|JNE REG', 'ETD', 'JNE']),
                'Address' => 'Jl. A, Indonesia',
            ]);

            $statusCol = $this->pickCol('detail__transactions', ['status', 'Status']);

            $dtRow = [
                'id_Detail_transaction' => 60011,
                'id_User' => 2,
                'id_product' => 6001,
                'qty' => 1,
                'Total' => 10000,
                'Transaksis_id' => 6001,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if ($statusCol) $dtRow[$statusCol] = 'Done';

            DB::table('detail__transactions')->insert($dtRow);

            $resp = (new TransaksiController())->viewTransaction(6001);
            $this->assertStringContainsString('view:User.Admin.ViewTransaction', (string)$resp->getContent());
        }
    }
}