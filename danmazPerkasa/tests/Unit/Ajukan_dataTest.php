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

    class ajukan_dataTest extends BaseControllerTestCase
    {
        

        /* =========================
     * AccountController UTB
     * ========================= */

        public function test_UTB_063_ajukan_data_updates_fields_and_redirects_transaction()
        {
            $this->requiresTablesOrSkip(['return_pesanans', 'detail__transactions', 'transaksis', 'products', 'users'], 'Need related tables');

            $this->seedUser();
            $this->seedProduct(['id_product' => 5]);
            $this->seedTransaksi(['id' => 10, 'id_user' => 2]);
            $this->seedDetailTransaction(['id_Detail_transaction' => 7, 'id_product' => 5, 'Transaksis_id' => 10]);

            $idRetur = $this->seedReturnPesanan(['Barang' => 5, 'id_detil_transaksi' => 7]);

            $req = $this->makeRequest([
                'id' => $idRetur,
                'alasan_retur' => 'rusak',
                'link_bukti' => 'http://x',
                'confirm_bukti' => 1,
                'confirm_norefund' => 1,
            ], 'POST');

            $res = (new ReturnPesananController())->ajukan_data($req);

            $this->assertStringContainsString('/Transaction/10', $res->getTargetUrl());
            $this->assertDatabaseHas('return_pesanans', ['id' => $idRetur, 'alasan_retur' => 'rusak']);
        }
    }
}