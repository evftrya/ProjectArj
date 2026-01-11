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

    class getAllDataTest extends BaseControllerTestCase
    {
        public function test_UTB_043_getAllData_pending_includes_pending_or_checkout()
        {
            $this->seedUser();

            $this->seedProduct([
                'id_product' => 5,
                'mainPhoto' => 1,
                'stok' => 10,
                'type' => 'Product',
            ]);
            $this->seedPhoto(['id_Photo' => 1, 'id_product' => 5, 'isMain' => 1]);

            $statusCol = $this->pickCol('detail__transactions', ['status', 'Status']);

            $pendingRow = [
                'id_Detail_transaction' => 7,
                'id_User' => 2,
                'id_product' => 5,
                'qty' => 1,
                'Total' => 10000,
                'Transaksis_id' => null,
            ];
            if ($statusCol) $pendingRow[$statusCol] = 'Pending';
            $this->seedDetailTransaction($pendingRow);

            $this->seedTransaksi([
                'id' => 10,
                'id_user' => 2,
                'type_transaction' => 'Product',
                'Status_Transaksi' => 'Waiting',
                'Status_Pembayaran' => 'Waiting',
                'Status_Pengiriman' => 'Waiting',
            ]);

            $checkoutRow = [
                'id_Detail_transaction' => 8,
                'id_User' => 2,
                'id_product' => 5,
                'qty' => 1,
                'Total' => 10000,
                'Transaksis_id' => 10,
            ];
            if ($statusCol) $checkoutRow[$statusCol] = 'Checkout';
            $this->seedDetailTransaction($checkoutRow);

            $this->setSession(['user_id' => 2, 'direction' => null]);

            $data = (new DetailTransactionController())->getAllData('Pending');
            $this->assertNotEmpty($data);

            $ids = [];
            foreach ($data as $row) {
                $id = $row->id_Detail_transaction ?? $row->id_detail_transaction ?? null;
                if ($id !== null) $ids[] = (int)$id;
            }
            $this->assertContains(7, $ids, 'Expected id_Detail_transaction 7 in result');
            $this->assertContains(8, $ids, 'Expected id_Detail_transaction 8 in result');

            if ($statusCol) {
                $st7 = DB::table('detail__transactions')->where('id_Detail_transaction', 7)->value($statusCol);
                $st8 = DB::table('detail__transactions')->where('id_Detail_transaction', 8)->value($statusCol);

                $this->assertSame('Pending', $st7);
                $this->assertSame('Checkout', $st8);
            } else {
                $this->markTestSkipped('No status/Status column exists on detail__transactions');
            }
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

        public function test_TestCase_080_getAllData_returns_rows_excluding_admin_id_1()
        {
            $this->requiresTablesOrSkip(['users'], 'users table required');

            $this->seedAdmin(['id_User' => 1]);
            $this->seedUser(['id_User' => 2, 'emailUser' => 'u2@a.com']);
            $this->seedUser(['id_User' => 3, 'emailUser' => 'u3@a.com']);

            $rows = (new AccountController())->getAllData();

            $this->assertNotEmpty($rows);
            foreach ($rows as $r) {
                $this->assertNotEquals(1, $r->id);
            }
        }
    }
}