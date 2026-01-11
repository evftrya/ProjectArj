<?php
namespace Tests\Feature {

    use Tests\TestCase;
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

    class cekLoginTest extends TestCase
    {
        use RefreshDatabase;

        protected function setUp(): void
        {
            parent::setUp();

            Route::get('/page-notfound', fn() => 'notfound')->name('page.notfound');
            Route::get('/fill-data/{retur_id}', fn($retur_id) => "fill-$retur_id")->name('fill-data');
        }

        /* =========================
     * Helpers (Schema-safe)
     * ========================= */

        private function requiresTablesOrSkip(array $tables, string $reason): void
        {
            foreach ($tables as $table) {
                if (!Schema::hasTable($table)) {
                    $this->markTestSkipped($reason . " (missing table: {$table})");
                }
            }
        }

        private function hasCol(string $table, string $col): bool
        {
            return Schema::hasColumn($table, $col);
        }

        private function pickCol(string $table, array $candidates): ?string
        {
            foreach ($candidates as $c) {
                if ($this->hasCol($table, $c)) return $c;
            }
            return null;
        }

        private function makeRequest(array $data = [], string $method = 'POST'): Request
        {
            return Request::create('/dummy', $method, $data);
        }

        private function setSession(array $data): void
        {
            foreach ($data as $k => $v) {
                session([$k => $v]);
            }
        }

        private function decodeJsonResponse($response)
        {
            return json_decode($response->getContent(), true);
        }

        /* =========================
     * Seeds (Schema-safe)
     * ========================= */

        private function seedUser(array $override = []): int
        {
            $this->requiresTablesOrSkip(['users'], 'Users table required');

            $row = array_merge([
                'id_User' => 2,
                'namaUser' => 'A B',
                'emailUser' => 'a@a.com',
                'passwordUser' => '123',
                'role' => 'User',
                'Phone' => '0812',
                'Gender' => 'M',
                'isActive' => 'active',
                'isDelete' => 'no',
                'created_at' => now(),
                'updated_at' => now(),
            ], $override);

            DB::table('users')->insert($row);
            return (int)$row['id_User'];
        }

        private function seedAdmin(array $override = []): int
        {
            return $this->seedUser(array_merge([
                'id_User' => 1,
                'emailUser' => 'admin@a.com',
                'role' => 'Admin',
            ], $override));
        }

        private function seedProduct(array $override = []): int
        {
            $this->requiresTablesOrSkip(['products'], 'Products table required');

            $row = array_merge([
                'id_product' => 5,
                'nama_product' => 'Prod',
                'stok' => 10,
                'price' => 10000,
                'originalPrice' => 8000,
                'weight' => 1000,
                'type' => 'Product',
                'isContent' => 0,
                'shortQuotes' => 'q',
                'color' => 'red',
                'detail_product' => 'desc',
                'Features' => 'feat',
                'mainPhoto' => null,
                'isSpecial' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ], $override);

            DB::table('products')->insert($row);
            return (int)$row['id_product'];
        }

        private function seedPhoto(array $override = []): int
        {
            $this->requiresTablesOrSkip(['photos'], 'Photos table required');

            $row = array_merge([
                'id_Photo' => 1,
                'PhotosName' => 'x.jpg',
                'id_product' => 5,
                'isMain' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], $override);

            DB::table('photos')->insert($row);
            return (int)$row['id_Photo'];
        }

        private function seedDetailTransaction(array $override = []): int
        {
            $this->requiresTablesOrSkip(['detail__transactions'], 'detail__transactions table required');

            $statusCol = $this->pickCol('detail__transactions', ['status', 'Status']);

            $base = [
                'id_Detail_transaction' => 7,
                'id_User' => 2,
                'id_product' => 5,
                'qty' => 1,
                'Total' => 10000,
                'Transaksis_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($statusCol) {
                $base[$statusCol] = 'Pending';
            }

            $row = array_merge($base, $override);

            if (isset($row['status']) && isset($row['Status'])) {
                unset($row['Status']);
            }

            DB::table('detail__transactions')->insert($row);
            return (int)$row['id_Detail_transaction'];
        }

        private function seedNotification(array $override = []): int
        {
            $this->requiresTablesOrSkip(['notifications'], 'Notifications table required');

            $userCol = $this->pickCol('notifications', ['id_user', 'id_User']);

            $row = array_merge([
                'idNotification' => 1,
                'type' => 'Transaction-Customer',
                'link' => '/Transaction/10',
                'Icon' => 0,
                'Title' => 'Transaction Successful',
                'Detil' => 'Tap for detail',
                'isRead' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ], $override);

            if ($userCol) {
                $row[$userCol] = $row[$userCol] ?? 2;
            }

            if (isset($row['id_user']) && isset($row['id_User'])) {
                unset($row['id_User']);
            }

            DB::table('notifications')->insert($row);
            return (int)$row['idNotification'];
        }

        private function seedProvinceCity(): void
        {
            $this->requiresTablesOrSkip(['provinces', 'cities'], 'provinces/cities tables required');

            if (!DB::table('provinces')->where('province_id', 1)->exists()) {
                DB::table('provinces')->insert([
                    'province_id' => 1,
                    'province_name' => 'Jawa',
                ]);
            }

            if (!DB::table('cities')->where('city_id', 10)->exists()) {
                DB::table('cities')->insert([
                    'city_id' => 10,
                    'province_id' => 1,
                    'city_name' => 'Bandung',
                ]);
            }
        }

        private function seedAddress(array $override = []): int
        {
            $this->requiresTablesOrSkip(['addresses'], 'Addresses table required');

            $row = array_merge([
                'id_user' => 2,
                'Provinsi' => 1,
                'KotaKabupaten' => 10,
                'Kecamatan' => 'Kec A',
                'Kelurahan' => 'Kel A',
                'RT' => '01',
                'RW' => '02',
                'KodePos' => '12345',
                'AlamatDetil' => 'Jl. A',
                'Detil' => 'Jl. A, Indonesia',
                'ShippingRate' => json_encode(['data' => []]),
                'created_at' => now(),
                'updated_at' => now(),
            ], $override);

            DB::table('addresses')->insert($row);

            $pk = $this->pickCol('addresses', ['id', 'id_address', 'id_Address', 'idAlamat', 'id_alamat']);
            if ($pk) {
                $latest = DB::table('addresses')->orderBy($pk, 'desc')->first();
                return (int)($latest->$pk ?? 0);
            }
            return 0;
        }

        private function seedTransaksi(array $override = []): int
        {
            $this->requiresTablesOrSkip(['transaksis'], 'transaksis table required');

            $row = array_merge([
                'id' => 10,
                'id_user' => 2,
                'TotalShopping' => 12000,
                'TotalShipping' => 2000,
                'Shipping' => json_encode(['JNE|REG|JNE REG', 'ETD', 'JNE']),
                'Notes' => null,
                'type_transaction' => 'Product',
                'Status_Pembayaran' => 'Waiting',
                'Status_Pengiriman' => 'Waiting',
                'Status_Transaksi' => 'Waiting',
                'PaymentMethod' => null,
                'Address' => 'Jl. A, Indonesia',
                'snapToken' => null,
                'shippingEstimate' => '2-3',
                'created_at' => now(),
                'updated_at' => now(),
            ], $override);

            DB::table('transaksis')->insert($row);
            return (int)$row['id'];
        }

        private function seedReturnPesanan(array $override = []): int
        {
            $this->requiresTablesOrSkip(['return_pesanans'], 'return_pesanans table required');

            $row = array_merge([
                'id' => 1,
                'Barang' => 5,
                'id_detil_transaksi' => 7,
                'qty_retur' => 1,
                'alasan_retur' => null,
                'link_bukti' => null,
                'persetujuan_1' => null,
                'persetujuan_2' => null,
                'retur_status' => null,
                'alasan_ditolak' => null,
                'Ekspedisi' => null,
                'Resi' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ], $override);

            if (Schema::hasTable('detail__transactions')) {
                $dtId = $row['id_detil_transaksi'] ?? 7;

                if (!DB::table('detail__transactions')->where('id_Detail_transaction', $dtId)->exists()) {
                    if (Schema::hasTable('users') && !DB::table('users')->where('id_User', 2)->exists()) {
                        $this->seedUser();
                    }
                    if (Schema::hasTable('products') && !DB::table('products')->where('id_product', ($row['Barang'] ?? 5))->exists()) {
                        $this->seedProduct(['id_product' => ($row['Barang'] ?? 5)]);
                    }

                    $this->seedDetailTransaction([
                        'id_Detail_transaction' => $dtId,
                        'id_product' => ($row['Barang'] ?? 5),
                    ]);
                }
            }

            DB::table('return_pesanans')->insert($row);
            return (int)$row['id'];
        }

        /* =========================
     * AccountController UTB
     * ========================= */

        public function test_UTB_005_cekLogin_wht_login_calls_cekExistEmail_with_pw()
        {
            $this->seedUser(['emailUser' => 'a@a.com', 'passwordUser' => '123']);
            $req = $this->makeRequest(['el' => 'a@a.com', 'pu' => '999'], 'POST');

            $res = (new AccountController())->cekLogin($req, 'Login');
            $data = $this->decodeJsonResponse($res);

            $this->assertArrayHasKey('message', $data);
            $this->assertSame('Wrong Password', $data['message']);
        }

        public function test_UTB_006_cekLogin_wht_not_login_calls_cekExistEmail_without_pw()
        {
            $this->seedUser(['emailUser' => 'a@a.com']);
            $req = $this->makeRequest(['el' => 'a@a.com'], 'POST');

            $res = (new AccountController())->cekLogin($req, 'Register');
            $data = $this->decodeJsonResponse($res);

            $this->assertArrayHasKey('message', $data);
            $this->assertStringContainsString('Email have been Exist', $data['message']);
        }
    }
}