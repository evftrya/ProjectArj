<?php
namespace Tests\Feature {

    use Tests\Unit\Support\BaseControllerTestCase;


    use Tests\TestCase;
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

    class DeactiveTest extends BaseControllerTestCase
    {

        public function test_UTB_019_Deactive_switch_active_to_nonActive()
        {
            $this->seedUser(['isActive' => 'active']);
            $res = (new AccountController())->Deactive(2);

            $this->assertSame(0, json_decode($res->getContent(), true));
            $this->assertDatabaseHas('users', ['id_User' => 2, 'isActive' => 'nonActive']);
        }

        public function test_UTB_020_Deactive_switch_nonActive_to_active()
        {
            $this->seedUser(['isActive' => 'nonActive']);
            $res = (new AccountController())->Deactive(2);

            $this->assertSame(1, json_decode($res->getContent(), true));
            $this->assertDatabaseHas('users', ['id_User' => 2, 'isActive' => 'active']);
        }
    }
}