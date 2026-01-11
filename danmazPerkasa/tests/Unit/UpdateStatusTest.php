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

    class UpdateStatusTest extends BaseControllerTestCase
    {


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

        public function test_TestCase_105_UpdateStatus_sets_checkout_status_when_wht_1()
        {
            $this->requiresTablesOrSkip(['detail__transactions'], 'detail__transactions required');

            $this->seedUser(['id_User' => 2]);
            $this->seedProduct(['id_product' => 5]);

            $statusCol = $this->pickColCI('detail__transactions', ['status', 'Status']);

            $row = [
                'id_Detail_transaction' => 901,
                'id_User' => 2,
                'id_product' => 5,
                'qty' => 1,
                'Total' => 10000,
                'Transaksis_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if ($statusCol) $row[$statusCol] = 'Pending';

            $this->insertCaseSafe('detail__transactions', $row);
            $this->setSession(['user_id' => 2]);

            $resp = (new DetailTransactionController())->UpdateStatus(5, '1');

            $this->assertInstanceOf(JsonResponse::class, $resp);

            $updated = DB::table('detail__transactions')->where('id_Detail_transaction', 901)->first();
            $col = $statusCol ?: $this->pickColCI('detail__transactions', ['status', 'Status']) ?: 'status';
            $this->assertSame('Checkout', $updated->$col);
        }
        public function test_TestCase_122_updateStatus_sets_WFP_branch()
        {
            $this->seedUser();
            $this->seedProduct();
            $this->setSession(['user_id' => 2]);

            $statusCol = $this->pickCol('detail__transactions', ['status', 'Status']);

            $seed = [
                'id_Detail_transaction' => 1221,
                'id_User' => 2,
                'id_product' => 5,
                'Transaksis_id' => null,
            ];
            if ($statusCol) $seed[$statusCol] = 'Pending';

            $this->seedDetailTransaction($seed);

            $resp = (new DetailTransactionController())->UpdateStatus(5, 'WFP');
            $this->assertInstanceOf(JsonResponse::class, $resp);

            $row = DB::table('detail__transactions')->where('id_Detail_transaction', 1221)->first();
            $this->assertSame('WFP', $this->getStatusValue($row));
        }

        public function test_TestCase_123_updateStatus_sets_donePayment_branch_to_Done()
        {
            $this->seedUser();
            $this->seedProduct();
            $this->setSession(['user_id' => 2]);

            $statusCol = $this->pickCol('detail__transactions', ['status', 'Status']);

            $seed = [
                'id_Detail_transaction' => 1231,
                'id_User' => 2,
                'id_product' => 5,
                'Transaksis_id' => null,
            ];
            if ($statusCol) $seed[$statusCol] = 'WFP';

            $this->seedDetailTransaction($seed);

            $resp = (new DetailTransactionController())->UpdateStatus(5, 'donePayment');
            $this->assertInstanceOf(JsonResponse::class, $resp);

            $row = DB::table('detail__transactions')->where('id_Detail_transaction', 1231)->first();
            $this->assertSame('Done', $this->getStatusValue($row));
        }

        public function test_TestCase_124_updateStatus_sets_default_branch_to_Pending()
        {
            $this->seedUser();
            $this->seedProduct();
            $this->setSession(['user_id' => 2]);

            $statusCol = $this->pickCol('detail__transactions', ['status', 'Status']);

            $seed = [
                'id_Detail_transaction' => 1241,
                'id_User' => 2,
                'id_product' => 5,
                'Transaksis_id' => null,
            ];
            if ($statusCol) $seed[$statusCol] = 'Checkout';

            $this->seedDetailTransaction($seed);

            $resp = (new DetailTransactionController())->UpdateStatus(5, 'anythingElse');
            $this->assertInstanceOf(JsonResponse::class, $resp);

            $row = DB::table('detail__transactions')->where('id_Detail_transaction', 1241)->first();
            $this->assertSame('Pending', $this->getStatusValue($row));
        }
        protected function getStatusValue(object $row): ?string
        {
            // schema-safe access
            return $row->status ?? ($row->Status ?? null);
        }
    }
}
