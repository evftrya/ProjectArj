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

    class LandingPageTest extends BaseControllerTestCase
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

        public function test_TestCase_138_landingPage_user_branch_returns_view_when_active()
        {
            $this->mockAllViews();

            $this->seedUser(['isActive' => 'active']);
            $this->setSession(['user_id' => 2, 'Role' => 'User', 'isActive' => 'active']);

            $this->seedProduct(['id_product' => 3001, 'type' => 'Product', 'isContent' => 1]);
            $this->seedPhoto(['id_Photo' => 3001, 'id_product' => 3001]);
            DB::table('products')->where('id_product', 3001)->update(['mainPhoto' => 3001]);

            $resp = (new ProductsController())->LandingPage();
            $this->assertStringContainsString('view:landingpage', (string)$resp->getContent());
        }

        public function test_TestCase_139_landingPage_user_branch_redirects_when_banned()
        {
            $this->seedUser(['isActive' => 'nonActive']);
            $this->setSession(['user_id' => 2, 'Role' => 'User', 'isActive' => 'nonActive']);

            $resp = (new ProductsController())->LandingPage();
            $this->assertInstanceOf(RedirectResponse::class, $resp);
            $this->assertStringContainsString('/BannedAccount', $resp->getTargetUrl());
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

        public function test_TestCase_157_landingPage_redirects_to_banned_when_non_admin_and_nonActive()
        {
            $this->requiresTablesOrSkip(
                ['users', 'products', 'photos', 'notifications'],
                'tables required'
            );

            $this->fakePublicStorage();

            $this->seedUser(['id_User' => 2]);
            $this->setSession(['user_id' => 2, 'Role' => 'User', 'isActive' => 'nonActive']);

            $pid = $this->seedProduct([
                'id_product'   => 777,
                'nama_product' => 'LP-Content',
                'type'         => 'Product',
                'isContent'    => 1,
                'mainPhoto'    => 0,
            ]);
            $ph = $this->seedPhoto([
                'id_Photo'   => 888,
                'id_product' => $pid,
                'isMain'     => 1,
            ]);
            DB::table('products')->where('id_product', $pid)->update(['mainPhoto' => $ph]);

            $resp = (new ProductsController())->LandingPage();

            $this->assertInstanceOf(RedirectResponse::class, $resp);
            $this->assertStringContainsString('/BannedAccount', $resp->getTargetUrl());
        }

        public function test_TestCase_158_landingPage_admin_dashboard_returns_view_and_runs_aggregations()
        {
            $this->requiresTablesOrSkip(
                ['users', 'products', 'detail__transactions', 'transaksis', 'notifications'],
                'tables required'
            );

            $this->mockAllViews();

            $this->seedAdmin(['id_User' => 1]);
            $this->seedUser(['id_User' => 2]);

            $this->setSession(['user_id' => 1, 'Role' => 'Admin', 'isActive' => 'active']);

            $pid = $this->seedProduct([
                'id_product'     => 901,
                'nama_product'   => 'AdminProd',
                'price'          => 20000,
                'originalPrice'  => 15000,
                'type'           => 'Product',
            ]);

            $tid = $this->seedTransaksi([
                'id'               => 9101,
                'id_user'          => 2,
                'type_transaction' => 'Product',
                'Status_Transaksi' => 'Acceptted',
                'created_at'       => now(),
                'updated_at'       => now(),
                'TotalShopping'    => 50000,
            ]);

            $this->seedDetailTransaction([
                'Transaksis_id' => $tid,
                'id_product'    => $pid,
                'id_User'       => 2,
                'qty'           => 2,
            ]);

            $resp = (new ProductsController())->LandingPage();

            $this->assertStringContainsString('view:User.Admin.AdminDashboard', (string)$resp->getContent());
        }
    }
}