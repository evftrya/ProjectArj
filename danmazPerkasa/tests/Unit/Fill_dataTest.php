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

    class fill_dataTest extends BaseControllerTestCase
    {
        

        /* =========================
     * AccountController UTB
     * ========================= */

        public function test_UTB_062_fill_data_returns_view_with_joined()
        {
            $this->requiresTablesOrSkip(['return_pesanans', 'detail__transactions', 'products'], 'Need return_pesanans/detail__transactions/products');

            $this->seedProduct(['id_product' => 5]);
            $this->seedUser();
            $this->seedDetailTransaction(['id_Detail_transaction' => 7, 'id_product' => 5]);

            $idRetur = $this->seedReturnPesanan(['Barang' => 5, 'id_detil_transaksi' => 7]);

            $res = (new ReturnPesananController())->fill_data($idRetur);
            $this->assertTrue(method_exists($res, 'render') || method_exists($res, 'getName'));
        }
    }
}