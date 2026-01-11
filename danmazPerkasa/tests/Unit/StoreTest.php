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
    use Illuminate\Http\UploadedFile;


    use App\Http\Controllers\AccountController;
    use App\Http\Controllers\AddressController;
    use App\Http\Controllers\Controller as BaseController;
    use App\Http\Controllers\DetailTransactionController;
    use App\Http\Controllers\NotificationController;
    use App\Http\Controllers\PhotosController;
    use App\Http\Controllers\ProductsController;
    use App\Http\Controllers\TransaksiController;
    use Illuminate\Support\Facades\Storage;
    use Mockery;

    class storeTest extends BaseControllerTestCase
    {

    public function test_UTB_021_address_store_creates_when_null()
        {
            $this->seedUser();
            $this->setSession(['user_id' => 2]);

            $req = $this->makeRequest([
                'provinsi' => 1,
                'KotaKabupaten' => 10,
                'Kecamatan' => 'Kec A',
                'Kelurahan' => 'Kel A',
                'RT' => '01',
                'RW' => '02',
                'KodePos' => '12345',
                'AlamatDetail' => 'Jl. A',
            ], 'POST');

            (new AddressController())->store($req, 'Jl. A, Indonesia');

            $this->assertDatabaseHas('addresses', ['id_user' => 2, 'Detil' => 'Jl. A, Indonesia']);
        }

        public function test_UTB_022_address_store_updates_when_exists()
        {
            $this->requiresTablesOrSkip(['addresses'], 'Addresses table is required for this test');

            $this->seedUser();
            $this->seedAddress(['Detil' => 'old']);
            $this->setSession(['user_id' => 2]);

            $req = $this->makeRequest([
                'provinsi' => 1,
                'KotaKabupaten' => 10,
                'Kecamatan' => 'Kec A',
                'Kelurahan' => 'Kel A',
                'RT' => '01',
                'RW' => '02',
                'KodePos' => '12345',
                'AlamatDetail' => 'Jl. A',
            ], 'POST');

            (new AddressController())->store($req, 'new');
            $this->assertDatabaseHas('addresses', ['id_user' => 2, 'Detil' => 'new']);
        }

        public function test_UTB_035_detail_store_get_method_redirect_notfound()
        {
            $req = $this->makeRequest([], 'GET');
            $res = (new DetailTransactionController())->store($req, 5);

            $this->assertStringContainsString('pagenotfound', strtolower($res->getTargetUrl()));
        }

        public function test_UTB_036_detail_store_not_auth_sets_direction_and_json_false()
        {
            $this->setSession(['user_id' => 0, 'direction' => null]);
            $req = $this->makeRequest(['qty' => 1], 'POST');

            $res = (new DetailTransactionController())->store($req, 5);
            $data = $this->decodeJsonResponse($res);

            $this->assertSame('false', $data['message']);
            $this->assertSame('/', session('direction'));
        }

        public function test_UTB_037_detail_store_existing_item_stock_ok_updates_qty()
        {
            $this->seedUser();
            $this->seedProduct(['stok' => 10, 'price' => 10000]);
            $this->seedDetailTransaction([
                'qty' => 1,
                'Total' => 10000,
                'Transaksis_id' => null,
            ]);
            $this->setSession(['user_id' => 2, 'direction' => null]);

            $req = $this->makeRequest(['qty' => 2], 'POST');
            $res = (new DetailTransactionController())->store($req, 5);
            $msg = json_decode($res->getContent(), true)['message'];

            $this->assertStringContainsString('successOld', $msg);
            $this->assertDatabaseHas('detail__transactions', [
                'id_User' => 2,
                'id_product' => 5,
                'qty' => 3,
            ]);
        }

        public function test_UTB_038_detail_store_existing_item_no_stock()
        {
            $this->seedUser();
            $this->seedProduct(['stok' => 0, 'price' => 10000]);
            $this->seedDetailTransaction([
                'qty' => 1,
                'Transaksis_id' => null,
            ]);
            $this->setSession(['user_id' => 2, 'direction' => null]);

            $req = $this->makeRequest(['qty' => 1], 'POST');
            $res = (new DetailTransactionController())->store($req, 5);

            $this->assertSame('NoStock', json_decode($res->getContent(), true)['message']);
        }

        public function test_UTB_039_detail_store_new_item_stock_ok_creates()
        {
            $this->seedUser();
            $this->seedProduct(['stok' => 10, 'price' => 10000]);
            $this->setSession(['user_id' => 2, 'direction' => null]);

            $req = $this->makeRequest(['qty' => 2], 'POST');
            $res = (new DetailTransactionController())->store($req, 5);

            $this->assertStringContainsString('successNew', $this->decodeJsonResponse($res)['message']);
            $this->assertDatabaseHas('detail__transactions', ['id_User' => 2, 'id_product' => 5]);
        }

        public function test_UTB_040_detail_store_new_item_no_stock()
        {
            $this->seedUser();
            $this->seedProduct(['stok' => 0, 'price' => 10000]);
            $this->setSession(['user_id' => 2, 'direction' => null]);

            $req = $this->makeRequest(['qty' => 1], 'POST');
            $res = (new DetailTransactionController())->store($req, 5);

            $this->assertSame('NoStock', $this->decodeJsonResponse($res)['message']);
        }

        public function test_UTB_046_notif_store_product_link()
        {
            $this->seedUser();
            (new NotificationController())->store(1, 99, 2);

            $this->assertDatabaseHas('notifications', ['link' => '/Detil-Product/99']);
        }

        public function test_UTB_047_notif_store_transaction_link_2_7()
        {
            $this->seedUser();
            (new NotificationController())->store(2, 10, 2);
            $this->assertDatabaseHas('notifications', ['link' => '/Transaction/10']);
        }

        public function test_UTB_048_notif_store_address_link()
        {
            $this->seedUser();
            (new NotificationController())->store(9, 0, 2);
            $this->assertDatabaseHas('notifications', ['link' => '/Profile/Address']);
        }

        public function test_UTB_049_notif_store_prevent_duplicate_when_title_matches()
        {
            $this->seedAdmin();

            $existing = [
                'idNotification' => 1,
                'Title' => 'New Order!',
                'link' => '/Transaction/10',
                'type' => 'Transaction-Admin',
                'Icon' => 6,
                'Detil' => 'Check For Details.',
                'isRead' => 0,
            ];
            $userCol = $this->pickCol('notifications', ['id_user', 'id_User']);
            if ($userCol) $existing[$userCol] = 1;

            $this->seedNotification($existing);

            (new NotificationController())->store(7, 10, 1);

            $query = DB::table('notifications')
                ->where('Title', 'New Order!')
                ->where('link', '/Transaction/10');

            if ($userCol) $query->where($userCol, 1);

            $this->assertSame(1, $query->count());
        }

        public function test_UTB_050_notif_store_allows_when_not_duplicate()
        {
            $this->seedAdmin();
            (new NotificationController())->store(7, 10, 1);

            $userCol = $this->pickCol('notifications', ['id_user', 'id_User']);
            if ($userCol) {
                $this->assertDatabaseHas('notifications', [$userCol => 1, 'Title' => 'New Order!']);
            } else {
                $this->assertDatabaseHas('notifications', ['Title' => 'New Order!']);
            }
        }

        public function test_UTB_053_photos_store_product_mainphoto_returns_id()
        {
            $this->seedProduct(['id_product' => 5]);
            Storage::fake('public');

            $file = UploadedFile::fake()->image('a.jpg', 10, 10);
            $req = $this->makeRequest(['mainPhoto' => 'foto1'], 'POST');
            $req->files->set('foto1', $file);

            $id = (new PhotosController())->store($req, 5, 1, 'Product');

            $this->assertNotNull($id);
            $this->assertDatabaseHas('photos', ['id_product' => 5, 'isMain' => 1]);
        }

        public function test_UTB_054_photos_store_product_nonmain_returns_null()
        {
            $this->seedProduct(['id_product' => 5]);
            Storage::fake('public');

            $file = UploadedFile::fake()->image('a.jpg', 10, 10);
            $req = $this->makeRequest(['mainPhoto' => 'foto2'], 'POST');
            $req->files->set('foto1', $file);

            $ret = (new PhotosController())->store($req, 5, 1, 'Product');

            $this->assertNull($ret);
            $this->assertDatabaseHas('photos', ['id_product' => 5]);
        }

        public function test_UTB_055_photos_store_part_always_main_returns_id()
        {
            $this->seedProduct(['id_product' => 5, 'type' => 'Part']);
            Storage::fake('public');

            $file = UploadedFile::fake()->image('a.jpg', 10, 10);
            $req = $this->makeRequest([], 'POST');
            $req->files->set('foto1', $file);

            $id = (new PhotosController())->store($req, 5, 1, 'Part');

            $this->assertNotNull($id);
            $this->assertDatabaseHas('photos', ['id_product' => 5, 'isMain' => 1]);
        }
        /* ===========================================================
     * Helpers (case-insensitive safe inserts & schema utils)
     * =========================================================== */

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

        public function test_TestCase_077_store_creates_user_and_redirects_to_login_when_email_not_exists()
        {
            $this->requiresTablesOrSkip(['users', 'notifications'], 'users/notifications tables required for AccountController::store');

            DB::table('users')->where('emailUser', 'new@a.com')->delete();

            $req = Request::create('/register', 'POST', [
                'firstName' => 'A',
                'lastName' => 'B',
                'emailUser' => 'new@a.com',
                'passwordUser' => '123',
            ]);

            $resp = (new AccountController())->store($req);

            $this->assertInstanceOf(RedirectResponse::class, $resp);
            $this->assertTrue(DB::table('users')->where('emailUser', 'new@a.com')->exists());
        }

        public function test_TestCase_113_store_creates_transaction_and_sets_fields()
        {
            $this->requiresTablesOrSkip(['users', 'products', 'detail__transactions', 'transaksis', 'addresses', 'provinces', 'cities'], 'tables required');

            // FIX: user + provinces/cities harus ada agar AddressController::getDetil tidak kosong
            $this->seedUser(['id_User' => 2, 'Phone' => '0812']);
            $this->seedProvinceCity();

            $pid = $this->seedProduct(['id_product' => 5, 'price' => 10000, 'stok' => 10]);

            $this->setSession(['user_id' => 2]);

            // create Checkout DT row
            $statusCol = $this->pickColCI('detail__transactions', ['status', 'Status']);
            $row = [
                'id_Detail_transaction' => 777,
                'id_User' => 2,
                'id_product' => $pid,
                'qty' => 1,
                'Total' => 10000,
                'Transaksis_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if ($statusCol) $row[$statusCol] = 'Checkout';
            $this->insertCaseSafe('detail__transactions', $row);

            // address
            $this->seedAddress([
                'id_user' => 2,
                'Detil' => 'Jl. A, Indonesia',
                'Provinsi' => 1,
                'KotaKabupaten' => 10,
            ]);

            $req = Request::create('/transaction/store', 'POST', [
                'shippingCost' => 2000,
                'ship' => 'JNE|REG|JNE REG',
                'shippingEstimate' => '2-3',
                'paymentMethod' => 'bank_transfer',
                'bankMethod' => 'bca',
                'ntoes' => 'note',
            ]);

            $resp = (new TransaksiController())->store($req, 'Default');

            $this->assertInstanceOf(RedirectResponse::class, $resp);
            $this->assertTrue(DB::table('transaksis')->where('id_user', 2)->exists());
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

        public function test_TestCase_126_products_store_part_saves_product_photos_category_and_redirects()
        {
            $this->mockAllViews();

            $this->requiresTablesOrSkip(
                ['users', 'products', 'photos', 'category_parts', 'ref_category_parts', 'notifications'],
                'tables required for ProductsController::store Part'
            );

            $this->seedAdmin();
            $this->seedUser(); // customer for notif fanout
            $this->seedRefCategoryParts(1, 'Cat', 'Area', 'T');

            Storage::fake('public');

            $file = UploadedFile::fake()->image('p1.jpg', 10, 10);

            $req = Request::create('/dummy', 'POST', [
                'ProductName' => 'PartX',
                'stock' => 10,
                'ProductPrice' => 10000,
                'originalPrice' => 8000,
                'ProductColor' => 'red',
                'shortQuotes' => 'q',
                'Description' => 'desc',
                'weight' => 1000,
                'TotalPhoto' => 1,
                'mainPhoto' => 'foto1',
                'product' => 1,
            ], [], [
                'foto1' => $file,
            ]);

            $resp = (new ProductsController())->store($req, 'Part');
            $this->assertInstanceOf(RedirectResponse::class, $resp);

            $this->assertTrue(DB::table('products')->where('nama_product', 'PartX')->exists());
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

        public function test_TestCase_154_store_creates_product_with_multiple_photos_and_sets_main_photo()
        {
            $this->requiresTablesOrSkip(
                ['users', 'products', 'photos', 'notifications', 'category_products', 'ref_category_products'],
                'tables required'
            );

            $this->fakePublicStorage();

            $this->seedAdmin(['id_User' => 1]);
            $this->setSession(['user_id' => 1, 'Role' => 'Admin', 'isActive' => 'active']);

            $this->seedRefCategoryProduct('Kategori-Test');

            $file1 = $this->fakeUploadImage('p1.jpg');
            $file2 = $this->fakeUploadImage('p2.jpg');

            $req = Request::create('/addproduct', 'POST', [
                'ProductName'     => 'Produk Baru X',
                'stock'           => 5,
                'ProductPrice'    => 15000,
                'originalPrice'   => 10000,
                'ProductColor'    => 'Merah',
                'shortQuotes'     => 'SQ',
                'Description'     => 'DESC',
                'weight'          => 500,
                'Features'        => 'F1,F2',
                'TotalPhoto'      => 2,
                'mainPhoto'       => 'foto2',
                'product'         => 'Kategori-Test',
            ], [], [
                'foto1' => $file1,
                'foto2' => $file2,
            ]);

            $resp = (new ProductsController())->store($req, 'Product');

            $this->assertInstanceOf(RedirectResponse::class, $resp);
            $this->assertTrue(DB::table('products')->where('nama_product', 'Produk Baru X')->exists());

            $productRow = DB::table('products')->where('nama_product', 'Produk Baru X')->first();
            $this->assertNotNull($productRow);

            $photos = DB::table('photos')->where('id_product', $productRow->id_product)->get();
            $this->assertTrue(count($photos) >= 2);

            $this->assertNotNull($productRow->mainPhoto);
        }
    }
}