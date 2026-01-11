<?php
namespace Tests\Unit\Controllers {

    use Tests\Unit\Support\BaseControllerTestCase;

    use Illuminate\Http\Request;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Facades\View as ViewFacade;

    use App\Http\Controllers\AccountController;
    use App\Http\Controllers\AddressController;
    use App\Http\Controllers\Controller as BaseController;
    use App\Http\Controllers\DetailTransactionController;
    use App\Http\Controllers\PhotosController;
    use App\Http\Controllers\ProductsController;
    use App\Http\Controllers\TransaksiController;

    class UpdatePartTest extends BaseControllerTestCase
    {
        protected function seedCategoryParts(int $idPart, int $idCategoryPart = 1): void
        {
            $this->requiresTablesOrSkip(['category_parts'], 'category_parts required');

            if (!DB::table('category_parts')->where('id_part', $idPart)->exists()) {
                DB::table('category_parts')->insert([
                    'id_part' => $idPart,
                    'id_category_part' => $idCategoryPart,
                ]);
            }
        }
        public function test_TestCase_135_updatePart_calls_update_and_redirects()
        {
            $this->requiresTablesOrSkip(['products', 'category_parts', 'ref_category_parts'], 'tables required');

            $this->seedRefCategoryParts(1, 'Cat', 'Area', 'T');
            $this->seedProduct(['id_product' => 1351, 'type' => 'Part']);
            $this->seedCategoryParts(1351, 1);

            $req = $this->makeRequest([
                'ProductName' => 'UpdPart',
                'stock' => 5,
                'ProductPrice' => 12000,
                'originalPrice' => 9000,
                'ProductColor' => 'blue',
                'Description' => 'desc2',
                'weight' => 1200,
                'TotalPhoto' => 0,
                'mainPhoto' => 'foto0',
                'product' => 1,
            ], 'POST');

            $resp = (new ProductsController())->updatePart($req, 1351);
            $this->assertInstanceOf(RedirectResponse::class, $resp);
        }
    }
}