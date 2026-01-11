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

    class authRouteTest extends BaseControllerTestCase
    {
        

        /* =========================
     * AccountController UTB
     * ========================= */

        public function test_UTB_032_authroute_authed_redirect_direction()
        {
            $this->setSession(['user_id' => 2]);
            $res = (new BaseAppController())->authRoute('/Cart');
            $this->assertStringContainsString('/Cart', $res->getTargetUrl());
        }

        public function test_UTB_033_authroute_not_authed_set_direction_and_redirect_login()
        {
            $this->setSession(['user_id' => 0]);
            $res = (new BaseAppController())->authRoute('/Cart');
            $this->assertStringContainsString('/Login', $res->getTargetUrl());
            $this->assertSame('/Cart', session('direction'));
        }

        public function test_UTB_034_authroute_not_authed_direction_login()
        {
            $this->setSession(['user_id' => 0]);
            $res = (new BaseAppController())->authRoute('/Login');
            $this->assertStringContainsString('/Login', $res->getTargetUrl());
        }
    }
}