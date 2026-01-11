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

    class RedAllNotifTest extends BaseControllerTestCase
    {
        

        /* =========================
     * AccountController UTB
     * ========================= */

        public function test_UTB_051_RedAllNotif_returns_fail_when_none_updated()
        {
            $this->seedUser();
            $this->setSession(['user_id' => 2]);

            $req = $this->makeRequest([], 'POST');
            $res = (new NotificationController())->RedAllNotif($req);

            $this->assertSame('fail', $this->decodeJsonResponse($res)['message']);
        }

        public function test_UTB_052_red_all_notif_returns_success_when_updated()
        {
            $this->seedUser();
            $this->setSession(['user_id' => 2]);

            $notif = [
                'idNotification' => 1,
                'isRead' => 0,
            ];
            $userCol = $this->pickCol('notifications', ['id_user', 'id_User']);
            if ($userCol) $notif[$userCol] = 2;

            $this->seedNotification($notif);

            $req = $this->makeRequest([], 'POST');
            $res = (new NotificationController())->RedAllNotif($req);

            $this->assertSame('success', json_decode($res->getContent(), true)['message']);
            $this->assertDatabaseHas('notifications', ['idNotification' => 1, 'isRead' => 1]);
        }
    }
}