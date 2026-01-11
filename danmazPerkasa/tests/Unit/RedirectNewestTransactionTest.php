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

    class RedirectNewestTransactionTest extends BaseControllerTestCase
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

        // protected function mockAllViews(): void
        // {
        //     // make any view() return a simple response-like object
        //     View::shouldReceive('make')->andReturnUsing(function ($view, $data = []) {
        //         return response("view:$view");
        //     })->byDefault();
        // }

        protected function getStatusValue(object $row): ?string
        {
            // schema-safe access
            return $row->status ?? ($row->Status ?? null);
        }

        /* ===========================================================
     * DetailTransactionController
     * =========================================================== */

        public function test_TestCase_151_redirectNewestTransaction_midtrans_200_redirects_transaction_page()
        {
            $this->requiresTablesOrSkip(['users', 'transaksis', 'notifications'], 'tables required');

            $this->seedAdmin(); // IMPORTANT: notif->store(..., idUser=1) requires admin exists
            $this->seedUser();
            $this->setSession(['user_id' => 2, 'Role' => 'User']);

            config(['app.env' => 'local']);
            putenv('MIDTRANS_SERVER_KEY=dummy');

            $this->seedTransaksi([
                'id' => 9001,
                'id_user' => 2,
                'Status_Pembayaran' => 'Waiting',
                'snapToken' => 'tok',
            ]);

            Http::fake([
                '*' => Http::response([
                    'status_code' => 200,
                    'payment_type' => 'bank_transfer',
                    'va_numbers' => [['bank' => 'bca']],
                    'transaction_status' => 'settlement',
                ], 200),
            ]);

            $resp = (new TransaksiController())->RedirectNewestTransaction();

            $this->assertInstanceOf(RedirectResponse::class, $resp);
            $this->assertStringContainsString('/Transaction/9001', $resp->getTargetUrl());
        }

        protected function mockAllViews(): void
        {
            View::shouldReceive('make')->andReturnUsing(function ($view, $data = []) {
                return response("view:$view");
            })->byDefault();
        }

        protected function seedRefCategoryProduct(string $name): void
        {
            if (!Schema::hasTable('ref_category_products')) return;
            if (DB::table('ref_category_products')->where('category_name', $name)->exists()) return;

            $row = [];
            if (Schema::hasColumn('ref_category_products', 'category_name')) $row['category_name'] = $name;

            if (Schema::hasColumn('ref_category_products', 'created_at')) $row['created_at'] = now();
            if (Schema::hasColumn('ref_category_products', 'updated_at')) $row['updated_at'] = now();

            if (Schema::hasColumn('ref_category_products', 'isDelete')) $row['isDelete'] = $row['isDelete'] ?? 'no';
            if (Schema::hasColumn('ref_category_products', 'isActive')) $row['isActive'] = $row['isActive'] ?? 'active';

            DB::table('ref_category_products')->insert($row);
        }

        /**
         * FIXED: isi kolom NOT NULL di ref_category_parts (Area, Category, Types, dst)
         */
        protected function seedRefCategoryPart(int $id, ?string $name = null): void
        {
            if (!Schema::hasTable('ref_category_parts')) return;
            if (DB::table('ref_category_parts')->where('id_category_part', $id)->exists()) return;

            $row = [];

            if (Schema::hasColumn('ref_category_parts', 'id_category_part')) {
                $row['id_category_part'] = $id;
            }

            // required columns based on your errors:
            if (Schema::hasColumn('ref_category_parts', 'Category')) {
                $row['Category'] = $name ?? ("CatPart-$id");
            }
            if (Schema::hasColumn('ref_category_parts', 'Area')) {
                $row['Area'] = 'TEST_AREA';
            }
            if (Schema::hasColumn('ref_category_parts', 'Types')) {
                // safest generic value
                $row['Types'] = 'Part';
            }

            // other possible name columns (if exist)
            $nameCol = null;
            foreach (['category_part', 'category_name', 'nama_category', 'name', 'NamaCategory'] as $c) {
                if (Schema::hasColumn('ref_category_parts', $c)) {
                    $nameCol = $c;
                    break;
                }
            }
            if ($nameCol) {
                $row[$nameCol] = $name ?? ("CatPart-$id");
            }

            // common extra required columns (best-effort)
            foreach (['Description', 'Keterangan', 'detail', 'Detail'] as $c) {
                if (Schema::hasColumn('ref_category_parts', $c) && !isset($row[$c])) {
                    $row[$c] = 'seeded by unit test';
                }
            }

            if (Schema::hasColumn('ref_category_parts', 'created_at')) $row['created_at'] = now();
            if (Schema::hasColumn('ref_category_parts', 'updated_at')) $row['updated_at'] = now();

            if (Schema::hasColumn('ref_category_parts', 'isDelete')) $row['isDelete'] = $row['isDelete'] ?? 'no';
            if (Schema::hasColumn('ref_category_parts', 'isActive')) $row['isActive'] = $row['isActive'] ?? 'active';

            DB::table('ref_category_parts')->insert($row);
        }

        /* ===========================================================
     * TestCase_154 - ProductsController@store (Product branch)
     * =========================================================== */

        public function test_TestCase_162_redirectNewestTransaction_202_deny_redirects_local_url_and_sets_reject_status()
        {
            $this->requiresTablesOrSkip(
                ['users', 'transaksis', 'detail__transactions', 'notifications', 'products'],
                'tables required'
            );

            $this->app->detectEnvironment(function () {
                return 'local';
            });

            $this->seedUser(['id_User' => 2, 'Phone' => '0812333']);
            $this->seedAdmin(['id_User' => 1]);
            $this->seedProduct(['id_product' => 5]);
            $this->setSession(['user_id' => 2, 'Role' => 'User']);

            $tid = $this->seedTransaksi([
                'id'                => 9301,
                'id_user'           => 2,
                'Status_Pembayaran' => 'Waiting',
                'snapToken'         => 'snap-y',
            ]);

            $this->seedDetailTransaction([
                'Transaksis_id' => $tid,
                'id_product'    => 5,
                'id_User'       => 2,
                'qty'           => 1,
            ]);

            Http::fake([
                'api.sandbox.midtrans.com/*' => Http::response([
                    'status_code'        => 202,
                    'payment_type'       => 'akulaku',
                    'transaction_status' => 'deny',
                    'va_numbers'         => [['bank' => 'bca']],
                ], 200),
            ]);

            $resp = (new TransaksiController())->RedirectNewestTransaction();

            $this->assertInstanceOf(RedirectResponse::class, $resp);
            $this->assertStringContainsString('http://127.0.0.1:8000/Transaction/' . $tid, $resp->getTargetUrl());

            $row = DB::table('transaksis')->where('id', $tid)->first();
            $this->assertEquals('Reject By akulaku', $row->Status_Pembayaran);
            $this->assertNull($row->snapToken);
        }
    }
}