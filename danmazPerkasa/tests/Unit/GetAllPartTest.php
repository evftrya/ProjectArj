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

    class getAllPartTest extends BaseControllerTestCase
    {
        /* ===========================================================
     * Helpers (case-insensitive safe inserts & schema utils)
     * =========================================================== */

        protected function mockViewHelper(): void
        {
            ViewFacade::shouldReceive('make')->andReturn('view-stub');
        }

        protected function tableColsLower(string $table): array
        {
            if (!Schema::hasTable($table)) return [];
            return array_map(fn($c) => strtolower($c), Schema::getColumnListing($table));
        }

        protected function pickColCI(string $table, array $candidates): ?string
        {
            $cols = Schema::hasTable($table) ? Schema::getColumnListing($table) : [];
            $map = [];
            foreach ($cols as $c) $map[strtolower($c)] = $c;

            foreach ($candidates as $cand) {
                $key = strtolower($cand);
                if (isset($map[$key])) return $map[$key];
            }
            return null;
        }

        protected function normalizeInsertRowCaseInsensitive(array $row): array
        {
            $out = [];
            $seen = [];
            foreach ($row as $k => $v) {
                $lk = strtolower($k);
                if (isset($seen[$lk])) continue;
                $seen[$lk] = true;
                $out[$k] = $v;
            }
            return $out;
        }

        protected function insertCaseSafe(string $table, array $row): void
        {
            $row = $this->normalizeInsertRowCaseInsensitive($row);
            DB::table($table)->insert($row);
        }

        /**
         * FK helper for your schema:
         * ref_category_parts.Area -> ref_area_category_parts.Area
         */
        protected function seedRefAreaCategoryParts(string $area): void
        {
            if (!Schema::hasTable('ref_area_category_parts')) {
                // kalau tabelnya gak ada, skip aja. Tapi dari error kamu, tabel ini ada.
                return;
            }

            if (!DB::table('ref_area_category_parts')->where('Area', $area)->exists()) {
                // Insert minimal required columns
                $row = ['Area' => $area];
                // kalau ada timestamps, aman ditambah (kalau tidak ada, akan diabaikan? tidak, bisa error)
                // jadi kita cek kolomnya dulu
                $cols = Schema::getColumnListing('ref_area_category_parts');
                if (in_array('created_at', $cols)) $row['created_at'] = now();
                if (in_array('updated_at', $cols)) $row['updated_at'] = now();

                $this->insertCaseSafe('ref_area_category_parts', $row);
            }
        }

        /* ===========================================================
     * AccountController
     * =========================================================== */

        public function test_TestCase_108_getAllPart_returns_arrays_with_data_area_category()
        {
            $this->requiresTablesOrSkip(
                ['products', 'category_parts', 'ref_category_parts', 'ref_area_category_parts', 'ref_category_products'],
                'tables required'
            );

            // FIX FK parent: ref_category_parts.Area -> ref_area_category_parts.Area
            $area = 'Area';
            $this->seedRefAreaCategoryParts($area);

            // FIX FK parent: ref_category_parts.Types -> ref_category_products.category_name
            $type = 'T';
            if (!DB::table('ref_category_products')->where('category_name', $type)->exists()) {
                $row = ['category_name' => $type];
                $cols = Schema::getColumnListing('ref_category_products');
                if (in_array('created_at', $cols)) $row['created_at'] = now();
                if (in_array('updated_at', $cols)) $row['updated_at'] = now();
                $this->insertCaseSafe('ref_category_products', $row);
            }

            if (!DB::table('ref_category_parts')->where('id_category_part', 1)->exists()) {
                $this->insertCaseSafe('ref_category_parts', [
                    'id_category_part' => 1,
                    'Category' => 'Cat',
                    'Area' => $area,
                    'Types' => $type,
                ]);
            }

            $pid = $this->seedProduct(['id_product' => 5, 'type' => 'Part']);

            $this->insertCaseSafe('category_parts', [
                'id_part' => $pid,
                'id_category_part' => 1,
            ]);

            $res = (new ProductsController())->getAllPart();
            $this->assertIsArray($res);
            $this->assertCount(3, $res);
        }
    }
}
