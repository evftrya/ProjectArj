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

    class tolakTest extends BaseControllerTestCase
    {
        

        /* =========================
     * AccountController UTB
     * ========================= */

        public function test_UTB_065_tolak_sets_false_and_reason()
        {
            $this->requiresTablesOrSkip(['return_pesanans', 'detail__transactions'], 'Need return_pesanans/detail__transactions');

            $idRetur = $this->seedReturnPesanan(['id' => 1, 'id_detil_transaksi' => 7]);

            $req = $this->makeRequest(['id_retur' => $idRetur, 'alasan_menolak' => 'tidak valid'], 'POST');
            (new ReturnPesananController())->tolak($req);

            $this->assertDatabaseHas('return_pesanans', [
                'id' => $idRetur,
                'retur_status' => 0,
                'alasan_ditolak' => 'tidak valid'
            ]);
        }
    }
}