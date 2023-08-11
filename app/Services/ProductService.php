<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\AvailablePlatformRepository;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductService
{
    /**
     * AvailablePlatformRepository
     *
     * @var \App\Repositories\AvailablePlatformRepository
     */
    protected $available_platform_repository;

    /**
     * ProductRepository
     *
     * @var \App\Repositories\ProductRepository
     */
    protected $product_repository;

    /**
     * 建構方法
     *
     * @param \App\Repositories\AvailablePlatformRepository $available_platform_repository
     * @param \App\Repositories\ProductRepository $product_repository
     * @return void
     */
    public function __construct(
        AvailablePlatformRepository $available_platform_repository,
        ProductRepository $product_repository
    ) {
        $this->available_platform_repository = $available_platform_repository;
        $this->product_repository = $product_repository;
    }

    /**
     * 取得作品清單
     *
     * @param int $page
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProductList(int $page): Collection
    {
        $products = $this->product_repository->getProductList($page);

        $products = $products->map(function (Model $product): Model {
            $product->platforms = json_decode($product->platforms, true);

            return $product;
        });

        return $products;
    }

    /**
     * 建立新作品
     *
     * @param string $title 作品標題
     * @param string $visual_image 作品主視覺圖
     * @param string $description 作品描述
     * @param string $type 作品類型
     * @param string|null $release_date 發售日期
     * @param string|null $url 作品網址
     * @return \App\Models\Product
     */
    public function createNewProduct(
        string $title,
        string $visual_image,
        string $description,
        string $type,
        string $release_date = null,
        string $url = null
    ): Product {
        $product = [
            'title' => $title,
            'visual_image' => $visual_image,
            'description' => $description,
            'product_url' => $url,
            'type' => $type,
            'release_at' => $release_date
        ];

        $new_product = $this->product_repository->create($product);

        return $new_product;
    }
}
