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

    class RejectOrderTest extends BaseControllerTestCase
    {
        

        /* =========================
     * AccountController UTB
     * ========================= */

        public function test_UTB_068_reject_order_sets_rejected_and_stock_plus()
        {
            $this->requiresTablesOrSkip(['transaksis', 'detail__transactions', 'products', 'users'], 'Need related tables');

            $this->seedUser();
            $this->seedTransaksi(['id' => 10, 'id_user' => 2, 'Status_Transaksi' => 'Waiting']);
            $this->seedProduct(['id_product' => 5, 'stok' => 7]);
            $this->seedDetailTransaction(['id_Detail_transaction' => 7, 'id_product' => 5, 'qty' => 3, 'Transaksis_id' => 10]);

            $res = (new TransaksiController())->RejectOrder(10);
            $this->assertSame('Success', json_decode($res->getContent(), true));

            $this->assertDatabaseHas('transaksis', ['id' => 10, 'Status_Transaksi' => 'Rejected']);
            $this->assertDatabaseHas('products', ['id_product' => 5, 'stok' => 10]);
        }
    }
}