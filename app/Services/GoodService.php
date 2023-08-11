<?php

namespace App\Services;

use App\Repositories\CartRepository;
use App\Repositories\GoodRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class GoodService
{
    /**
     * CartRepository
     *
     * @var \App\Repositories\CartRepository
     */
    protected $cart_repository;

    /**
     * GoodRepository
     *
     * @var \App\Repositories\GoodRepository
     */
    protected $good_repository;

    /**
     * 建構方法
     *
     * @param \App\Repositories\CartRepository $cart_repository
     * @param \App\Repositories\GoodRepository $good_repository
     * @return void
     */
    public function __construct(
        CartRepository $cart_repository,
        GoodRepository $good_repository
    ) {
        $this->cart_repository = $cart_repository;
        $this->good_repository = $good_repository;
    }

    /**
     * 取得商品列表
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getGoodsList(): Collection
    {
        return $this->good_repository->getGoodsList();
    }

    /**
     * 取得商品資料
     *
     * @param int $id
     * @return \App\Models\Good
     *
     * @throws \InvalidArgumentException
     */
    public function getGood(int $id)
    {
        return $this->good_repository->getGood($id);
    }

    /**
     * 以 ID 取得商品詳細資料
     *
     * @param array<int, int> $goods 商品 IDs
     * @return \Illuminate\Support\Collection
     */
    public function getCartGoodsByIds(array $goods): SupportCollection
    {
        return $this->good_repository->getCartGoodsByIds($goods);
    }

    /**
     * 儲存購物車內容
     *
     * @param array<int, int> $cart 購物車內商品 IDs
     * @return void
     */
    public function saveCart(array $cart): void
    {
        $this->cart_repository->deleteCartByUserId(1);

        $data = collect($cart)
            ->map(function (array $good) {
                return [
                    'user_id' => 1,
                    'good_id' => $good['id'],
                    'quantity' => $good['quantity'],
                    'price' => $good['price'],
                ];
            })
            ->toArray();

        $this->cart_repository->createMultipleRecords($data);
    }

    /**
     * 重置購物車
     *
     * @param int $user_id 帳號 ID
     * @return void
     */
    public function resetCart(int $user_id): void
    {
        $this->cart_repository->deleteCartByUserId($user_id);
    }

    /**
     * 以帳號 ID 取得已儲存購物車內容
     *
     * @param int $user_id 帳號 ID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSavedCart(int $user_id): Collection
    {
        return $this->cart_repository->getSavedCartByUserId($user_id);
    }
}
