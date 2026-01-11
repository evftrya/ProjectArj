<?php
namespace Tests\Unit\Controllers {

    use Tests\Unit\Support\BaseControllerTestCase;

    use Illuminate\Http\Request;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Facades\View as ViewFacade;
    use Illuminate\Support\Facades\View;

    use App\Http\Controllers\AccountController;
    use App\Http\Controllers\AddressController;
    use App\Http\Controllers\Controller as BaseController;
    use App\Http\Controllers\DetailTransactionController;
    use App\Http\Controllers\PhotosController;
    use App\Http\Controllers\ProductsController;
    use App\Http\Controllers\TransaksiController;
    use Mockery;

    class updateTest extends BaseControllerTestCase
    {

        /* =========================
     * AccountController UTB
     * ========================= */

        public function test_UTB_013_update_info_branch_updates_fields()
        {
            $this->seedUser(['namaUser' => 'Old Name', 'emailUser' => 'old@a.com']);
            $this->setSession(['user_id' => 2]);

            $req = $this->makeRequest([
                'firstName' => 'New',
                'lastName' => 'Name',
                'emailUser' => 'new@a.com',
                'Phone' => '0813',
                'Gender' => 'F',
            ], 'POST');

            $res = (new AccountController())->update($req, 'Info');

            $this->assertStringContainsString('/Profile/Info', $res->getTargetUrl());
            $this->assertDatabaseHas('users', ['id_User' => 2, 'emailUser' => 'new@a.com', 'namaUser' => 'New Name']);
        }

        public function test_UTB_014_update_changePassword_current_null_skips_change()
        {
            $this->seedUser(['passwordUser' => 'old']);
            $this->setSession(['user_id' => 2]);

            $req = $this->makeRequest(['currentPassword' => null], 'POST');
            $res = (new AccountController())->update($req, 'ChangePassword');

            $this->assertStringContainsString('/Profile/Change-Password', $res->getTargetUrl());
            $this->assertDatabaseHas('users', ['id_User' => 2, 'passwordUser' => 'old']);
        }

        public function test_UTB_015_update_changePassword_current_not_match()
        {
            $this->seedUser(['passwordUser' => 'old']);
            $this->setSession(['user_id' => 2]);

            $req = $this->makeRequest([
                'currentPassword' => 'x',
                'NewPassword' => 'new',
                'RetypeNewPassword' => 'new',
            ], 'POST');

            $res = (new AccountController())->update($req, 'ChangePassword');
            $this->assertStringContainsString('/Profile/Change-Password', $res->getTargetUrl());
            $this->assertDatabaseHas('users', ['id_User' => 2, 'passwordUser' => 'old']);
        }

        public function test_UTB_016_update_changePassword_new_not_match_retype()
        {
            $this->seedUser(['passwordUser' => 'old']);
            $this->setSession(['user_id' => 2]);

            $req = $this->makeRequest([
                'currentPassword' => 'old',
                'NewPassword' => 'a',
                'RetypeNewPassword' => 'b',
            ], 'POST');

            $res = (new AccountController())->update($req, 'ChangePassword');
            $this->assertStringContainsString('/Profile/Change-Password', $res->getTargetUrl());
            $this->assertDatabaseHas('users', ['id_User' => 2, 'passwordUser' => 'old']);
        }

        public function test_UTB_017_update_changePassword_success()
        {
            $this->seedUser(['passwordUser' => 'old']);
            $this->setSession(['user_id' => 2]);

            $req = $this->makeRequest([
                'currentPassword' => 'old',
                'NewPassword' => 'new',
                'RetypeNewPassword' => 'new',
            ], 'POST');

            (new AccountController())->update($req, 'ChangePassword');
            $this->assertDatabaseHas('users', ['id_User' => 2, 'passwordUser' => 'new']);
        }

        public function test_UTB_018_update_address_branch_saves_address()
        {
            $this->seedUser();
            $this->seedProvinceCity();
            $this->setSession(['user_id' => 2]);

            $req = $this->makeRequest([
                '_token' => 't',
                'provinsi' => 1,
                'KotaKabupaten' => 10,
                'Kecamatan' => 'Kec A',
                'Kelurahan' => 'Kel A',
                'RT' => '01',
                'RW' => '02',
                'KodePos' => '12345',
                'AlamatDetail' => 'Jl. A',
            ], 'POST');

            $res = (new AccountController())->update($req, 'Address');
            $this->assertStringContainsString('/Profile/Address', $res->getTargetUrl());
        }

        protected function mockViewHelper(): void
        {
            ViewFacade::shouldReceive('make')->andReturn('view-stub');
        }

        protected function tableColsLower(string $table): array
        {
            if (!Schema::hasTable($table)) return [];
            return array_map(fn($c) => strtolower($c), Schema::getColumnListing($table));
        }

        protected function pickColCI(string $table, array $candidates): ?string
        {
            $cols = Schema::hasTable($table) ? Schema::getColumnListing($table) : [];
            $map = [];
            foreach ($cols as $c) $map[strtolower($c)] = $c;

            foreach ($candidates as $cand) {
                $key = strtolower($cand);
                if (isset($map[$key])) return $map[$key];
            }
            return null;
        }

        protected function normalizeInsertRowCaseInsensitive(array $row): array
        {
            $out = [];
            $seen = [];
            foreach ($row as $k => $v) {
                $lk = strtolower($k);
                if (isset($seen[$lk])) continue;
                $seen[$lk] = true;
                $out[$k] = $v;
            }
            return $out;
        }

        protected function insertCaseSafe(string $table, array $row): void
        {
            $row = $this->normalizeInsertRowCaseInsensitive($row);
            DB::table($table)->insert($row);
        }

        /**
         * FK helper for your schema:
         * ref_category_parts.Area -> ref_area_category_parts.Area
         */
        protected function seedRefAreaCategoryParts(string $area): void
        {
            if (!Schema::hasTable('ref_area_category_parts')) {
                // kalau tabelnya gak ada, skip aja. Tapi dari error kamu, tabel ini ada.
                return;
            }

            if (!DB::table('ref_area_category_parts')->where('Area', $area)->exists()) {
                // Insert minimal required columns
                $row = ['Area' => $area];
                // kalau ada timestamps, aman ditambah (kalau tidak ada, akan diabaikan? tidak, bisa error)
                // jadi kita cek kolomnya dulu
                $cols = Schema::getColumnListing('ref_area_category_parts');
                if (in_array('created_at', $cols)) $row['created_at'] = now();
                if (in_array('updated_at', $cols)) $row['updated_at'] = now();

                $this->insertCaseSafe('ref_area_category_parts', $row);
            }
        }

        /* ===========================================================
     * AccountController
     * =========================================================== */

        public function test_TestCase_078_update_info_updates_user_fields_and_redirects_profile_info()
        {
            $this->requiresTablesOrSkip(['users'], 'users table required');

            $uid = $this->seedUser(['id_User' => 2, 'namaUser' => 'Old Name', 'emailUser' => 'old@a.com']);
            $this->setSession(['user_id' => $uid]);

            $req = Request::create('/profile/update', 'POST', [
                'firstName' => 'New',
                'lastName' => 'Name',
                'emailUser' => 'new@a.com',
                'Phone' => '081234',
                'Gender' => 'F',
            ]);

            $resp = (new AccountController())->update($req, 'Info');

            $this->assertInstanceOf(RedirectResponse::class, $resp);

            $row = DB::table('users')->where('id_User', $uid)->first();
            $this->assertSame('New Name', $row->namaUser);
            $this->assertSame('new@a.com', $row->emailUser);
            $this->assertSame('081234', $row->Phone);
            $this->assertSame('F', $row->Gender);
            $this->assertSame('New Name', session('user_name'));
        }

        public function test_TestCase_079_update_change_password_updates_when_old_matches_and_new_equals_retype()
        {
            $this->requiresTablesOrSkip(['users'], 'users table required');

            $uid = $this->seedUser(['id_User' => 2, 'passwordUser' => 'oldpw']);
            $this->setSession(['user_id' => $uid]);

            $req = Request::create('/profile/update', 'POST', [
                'currentPassword' => 'oldpw',
                'NewPassword' => 'newpw',
                'RetypeNewPassword' => 'newpw',
            ]);

            $resp = (new AccountController())->update($req, 'ChangePassword');

            $this->assertInstanceOf(RedirectResponse::class, $resp);
            $row = DB::table('users')->where('id_User', $uid)->first();
            $this->assertSame('newpw', $row->passwordUser);
        }

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

        public function test_TestCase_134_updateProduct_calls_update_and_redirects()
        {
            $this->mockAllViews();

            $this->requiresTablesOrSkip(['products', 'category_products'], 'products/category_products required');

            $this->seedProduct(['id_product' => 1341, 'type' => 'Product']);
            $this->seedCategoryProducts(1341, 'T');

            $req = $this->makeRequest([
                'ProductName' => 'Upd',
                'stock' => 5,
                'ProductPrice' => 12000,
                'originalPrice' => 9000,
                'ProductColor' => 'blue',
                'Description' => 'desc2',
                'weight' => 1200,
                'TotalPhoto' => 0,
                'mainPhoto' => 'foto0',
                'product' => 'T',
            ], 'POST');

            $resp = (new ProductsController())->updateProduct($req, 1341);
            $this->assertInstanceOf(RedirectResponse::class, $resp);
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

        public function test_TestCase_155_update_product_changes_main_photo_when_mainPhoto_not_foto0()
        {
            $this->requiresTablesOrSkip(
                ['products', 'photos', 'category_products', 'ref_category_products'],
                'tables required'
            );

            $this->fakePublicStorage();

            $this->seedRefCategoryProduct('OldCat');
            $this->seedRefCategoryProduct('NewCat');

            $pid = $this->seedProduct([
                'id_product'   => 501,
                'nama_product' => 'ProdukU',
                'type'         => 'Product',
                'mainPhoto'    => 0,
            ]);

            DB::table('category_products')->insert([
                'id_product'    => $pid,
                'category_name' => 'OldCat',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            $this->seedPhoto([
                'id_Photo'   => 701,
                'id_product' => $pid,
                'isMain'     => 1,
                'PhotosName' => 'a.jpg',
            ]);
            $ph2 = $this->seedPhoto([
                'id_Photo'   => 702,
                'id_product' => $pid,
                'isMain'     => 0,
                'PhotosName' => 'b.jpg',
            ]);

            $req = Request::create('/updateProduct', 'POST', [
                'ProductName'   => 'ProdukU-Updated',
                'stock'         => 99,
                'ProductPrice'  => 25000,
                'originalPrice' => 20000,
                'ProductColor'  => 'Hitam',
                'Description'   => 'New Desc',
                'weight'        => 123,
                'TotalPhoto'    => 1,
                'mainPhoto'     => 'foto2',
                'product'       => 'NewCat',
            ]);

            (new ProductsController())->update($req, $pid, 'Product');

            $row = DB::table('products')->where('id_product', $pid)->first();
            $this->assertEquals('ProdukU-Updated', $row->nama_product);
            $this->assertEquals((int)$ph2, (int)$row->mainPhoto);

            $catRow = DB::table('category_products')->where('id_product', $pid)->first();
            $this->assertEquals('NewCat', $catRow->category_name);
        }
    }
}