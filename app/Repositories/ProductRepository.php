<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProductRepository extends BaseRepository
{
    /**
     * Model 中文
     *
     * @return string
     */
    protected function name(): string
    {
        return '作品';
    }

    /**
     * 建構方法
     *
     * @param \App\Models\Product $product
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    /**
     * 取得作品清單
     *
     * @param int $page 頁碼
     * @param int $per_page (10) 每頁筆數
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProductList(int $page, int $per_page = 10): Collection
    {
        $page -= 1;
        $start = $page * $per_page;

        return $this->model
            ->select(
                'products.*',
                DB::raw('JSON_ARRAYAGG(ap.platform) AS platforms')
            )
            ->join('available_platforms AS ap', 'ap.product_id', 'products.id')
            ->groupBy('products.id')
            ->skip($start)
            ->take($per_page)
            ->get();
    }
}
