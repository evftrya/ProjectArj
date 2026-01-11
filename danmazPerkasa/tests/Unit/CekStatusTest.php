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

    use App\Http\Controllers\AccountController;
    use App\Http\Controllers\AddressController;
    use App\Http\Controllers\Controller as BaseController;
    use App\Http\Controllers\DetailTransactionController;
    use App\Http\Controllers\PhotosController;
    use App\Http\Controllers\ProductsController;
    use App\Http\Controllers\TransaksiController;

    class cekStatusTest extends BaseControllerTestCase
    {
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



        public function test_TestCase_115_cekStatus_updates_status_when_midtrans_returns_200()
        {
            $this->requiresTablesOrSkip(['users', 'transaksis', 'notifications'], 'tables required');

            $this->seedUser(['id_User' => 2, 'Phone' => '0812']);
            $this->seedAdmin(['id_User' => 1]);
            $this->seedTransaksi([
                'id' => 10,
                'id_user' => 2,
                'Status_Pembayaran' => 'Waiting',
            ]);

            $this->setSession(['user_id' => 2]);

            putenv('MIDTRANS_SERVER_KEY=server');
            putenv('CODE_TRANSACTION=XYZ');

            Http::fake([
                'https://api.sandbox.midtrans.com/v2/*/status' => Http::response([
                    'status_code' => 200,
                    'payment_type' => 'bank_transfer',
                    'va_numbers' => [
                        ['bank' => 'bca'],
                    ],
                ], 200),
            ]);

            $resp = (new TransaksiController())->cekStatus(10);

            $this->assertInstanceOf(JsonResponse::class, $resp);
            $status = $this->decodeJsonResponse($resp);
            $this->assertSame('Done', $status);

            $trx = DB::table('transaksis')->where('id', 10)->first();
            $this->assertSame('Done', $trx->Status_Pembayaran);
        }

        public function test_TestCase_161_cekStatus_sets_reject_by_payment_type_when_midtrans_returns_202_deny()
        {
            $this->requiresTablesOrSkip(
                ['users', 'transaksis', 'detail__transactions', 'notifications', 'products'],
                'tables required'
            );

            $this->seedUser(['id_User' => 2, 'Phone' => '0812000']);
            $this->seedAdmin(['id_User' => 1]);
            $this->seedProduct(['id_product' => 5]);
            $this->setSession(['user_id' => 2, 'Role' => 'User']);

            $tid = $this->seedTransaksi([
                'id'                => 9201,
                'id_user'           => 2,
                'Status_Pembayaran' => 'Waiting',
                'snapToken'         => 'snap-x',
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

            $resp = (new TransaksiController())->cekStatus($tid);

            $this->assertInstanceOf(JsonResponse::class, $resp);
            $this->assertEquals('Reject By akulaku', json_decode($resp->getContent(), true));

            $row = DB::table('transaksis')->where('id', $tid)->first();
            $this->assertEquals('Reject By akulaku', $row->Status_Pembayaran);
            $this->assertNull($row->snapToken);
        }
    }
}
