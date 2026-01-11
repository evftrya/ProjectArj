<?php

namespace Tests\Feature {

    use Tests\Unit\Support\BaseControllerTestCase;
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
    use Illuminate\Http\RedirectResponse;


    class CancelTransactionTest extends BaseControllerTestCase
    {
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

        public function test_UTB_069_cancelTransaction_transaction_null_redirect_notfound()
        {
            $this->seedUser();
            $this->setSession(['user_id' => 2]);

            $res = (new TransaksiController())->CancelTransaction('9991');
            $this->assertStringContainsString('/PageNotFound', $res->getTargetUrl());
        }

        public function test_UTB_070_cancel_transaction_success_type1_back_and_restore_stock()
        {
            $this->requiresTablesOrSkip(['transaksis', 'detail__transactions', 'products', 'users'], 'Need related tables');

            $this->seedUser();
            $this->setSession(['user_id' => 2]);
            $this->seedTransaksi(['id' => 10, 'id_user' => 2]);
            $this->seedProduct(['id_product' => 5, 'stok' => 7]);
            $this->seedDetailTransaction(['id_Detail_transaction' => 7, 'id_product' => 5, 'qty' => 3, 'Transaksis_id' => 10]);

            (new TransaksiController())->CancelTransaction('101');

            $this->assertDatabaseHas('transaksis', ['id' => 10, 'Status_Transaksi' => 'Cancel']);
            $this->assertDatabaseHas('products', ['id_product' => 5, 'stok' => 10]);
        }

        public function test_UTB_071_cancel_transaction_success_type0_returns_json()
        {
            $this->requiresTablesOrSkip(['transaksis', 'detail__transactions', 'products', 'users'], 'Need related tables');

            $this->seedUser();
            $this->setSession(['user_id' => 2]);
            $this->seedTransaksi(['id' => 10, 'id_user' => 2]);
            $this->seedProduct(['id_product' => 5, 'stok' => 7]);
            $this->seedDetailTransaction(['id_Detail_transaction' => 7, 'id_product' => 5, 'qty' => 3, 'Transaksis_id' => 10]);

            $res = (new TransaksiController())->CancelTransaction('100');
            $this->assertStringContainsString('The transaction has been canceled', json_decode($res->getContent(), true));
        }
        public function test_TestCase_117_CancelTransaction_sets_cancel_and_restores_stock()
        {
            $this->requiresTablesOrSkip(['users', 'products', 'detail__transactions', 'transaksis', 'notifications'], 'tables required');

            $this->seedUser(['id_User' => 2]);
            $pid = $this->seedProduct(['id_product' => 5, 'stok' => 0, 'price' => 10000]);

            $this->seedTransaksi(['id' => 10, 'id_user' => 2, 'Status_Transaksi' => 'Waiting']);

            $this->insertCaseSafe('detail__transactions', [
                'id_Detail_transaction' => 1001,
                'id_User' => 2,
                'id_product' => $pid,
                'qty' => 2,
                'Total' => 20000,
                'Transaksis_id' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->setSession(['user_id' => 2]);

            $resp = (new TransaksiController())->CancelTransaction('101');

            $this->assertInstanceOf(RedirectResponse::class, $resp);

            $trx = DB::table('transaksis')->where('id', 10)->first();
            $this->assertSame('Cancel', $trx->Status_Transaksi);

            $prod = DB::table('products')->where('id_product', $pid)->first();
            $this->assertSame(2, (int)$prod->stok);
        }
    }
}
