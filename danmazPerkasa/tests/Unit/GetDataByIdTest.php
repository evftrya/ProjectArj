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

    class getDataByIdTest extends BaseControllerTestCase
    {
        

        /* =========================
     * AccountController UTB
     * ========================= */

        public function test_UTB_023_getDataById_when_exists_returns_joined()
        {
            $this->requiresTablesOrSkip(['addresses', 'provinces', 'cities'], 'Join requires provinces & cities tables');

            $this->seedUser();
            $this->seedProvinceCity();
            $this->seedAddress(['Provinsi' => 1, 'KotaKabupaten' => 10]);
            $this->setSession(['user_id' => 2]);

            $data = (new AddressController())->getDataById();
            $this->assertNotNull($data[0]->province_name ?? null);
            $this->assertNotNull($data[0]->city_name ?? null);
        }

        public function test_UTB_024_getDataById_when_null_returns_default_stdclass()
        {
            $this->seedUser();
            $this->setSession(['user_id' => 2]);

            $data = (new AddressController())->getDataById();

            $this->assertSame('Alamat Belum Diisi', $data[0]->Detil);
        }
    }
}