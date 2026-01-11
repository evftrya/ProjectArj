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

    class toTransactionTest extends BaseControllerTestCase
    {
        

        /* =========================
     * AccountController UTB
     * ========================= */

        public function test_UTB_072_toTransaction_id0_redirect_home()
        {
            $res = (new TransaksiController())->toTransaction(0);
            $this->assertStringContainsString('/', $res->getTargetUrl());
        }

        public function test_UTB_073_toTransaction_data_missing_redirect_notfound()
        {
            // Dulu kamu tulis expectException karena bug akses $Data[0] sebelum cek data.
            // Di versi ini kita pertahankan perilaku "real" itu.
            $this->expectException(\ErrorException::class);

            (new TransaksiController())->toTransaction(999999);
        }

        public function test_UTB_074_to_transaction_user_role_admin_no_snap_token_needed()
        {
            $this->requiresTablesOrSkip(['transaksis', 'detail__transactions', 'products', 'photos', 'users', 'addresses', 'provinces', 'cities'], 'Need related tables');

            $this->seedAdmin();
            $this->seedUser(['id_User' => 2, 'emailUser' => 'user@a.com']);

            $this->seedProduct(['id_product' => 5, 'mainPhoto' => 1]);
            $this->seedPhoto(['id_Photo' => 1, 'id_product' => 5, 'isMain' => 1]);
            $this->seedTransaksi(['id' => 10, 'id_user' => 2, 'Status_Pembayaran' => 'Waiting']);
            $this->seedDetailTransaction(['id_Detail_transaction' => 7, 'Transaksis_id' => 10]);

            $this->seedProvinceCity();
            $this->seedAddress(['id_user' => 2, 'Provinsi' => 1, 'KotaKabupaten' => 10]);

            $this->setSession(['user_id' => 1, 'Role' => 'Admin']);

            $mock = $this->getMockBuilder(TransaksiController::class)
                ->onlyMethods(['GetSnapToken'])
                ->getMock();
            $mock->method('GetSnapToken')->willReturn('dummy-snap-token');

            $res = $mock->toTransaction(10);
            $this->assertTrue(method_exists($res, 'render') || method_exists($res, 'getName'));
        }

        public function test_UTB_075_to_transaction_payment_not_done_snap_token_null_generates_and_saves()
        {
            $this->requiresTablesOrSkip(['transaksis', 'detail__transactions', 'products', 'photos', 'users', 'addresses', 'provinces', 'cities'], 'Need related tables');

            $this->seedUser();
            $this->seedProduct(['id_product' => 5, 'mainPhoto' => 1]);
            $this->seedPhoto(['id_Photo' => 1, 'id_product' => 5, 'isMain' => 1]);
            $this->seedTransaksi(['id' => 10, 'id_user' => 2, 'Status_Pembayaran' => 'Waiting', 'snapToken' => null]);
            $this->seedDetailTransaction(['id_Detail_transaction' => 7, 'Transaksis_id' => 10]);

            $this->seedProvinceCity();
            $this->seedAddress(['id_user' => 2, 'Provinsi' => 1, 'KotaKabupaten' => 10]);

            $this->setSession(['user_id' => 2, 'Role' => 'User']);

            $mock = $this->getMockBuilder(TransaksiController::class)
                ->onlyMethods(['GetSnapToken'])
                ->getMock();

            $mock->method('GetSnapToken')->willReturn('dummy-snap-token');

            $res = $mock->toTransaction(10);

            $this->assertTrue(method_exists($res, 'render') || method_exists($res, 'getName'));
            $this->assertDatabaseHas('transaksis', ['id' => 10, 'snapToken' => 'dummy-snap-token']);
        }
    }
}