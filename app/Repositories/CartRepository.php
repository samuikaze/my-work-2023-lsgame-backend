<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CartRepository extends BaseRepository
{
    /**
     * Model 中文
     *
     * @return string
     */
    protected function name(): string
    {
        return '購物車';
    }

    /**
     * 建構方法
     *
     * @param \App\Models\Cart $cart
     * @return void
     */
    public function __construct(Cart $cart)
    {
        $this->model = $cart;
    }

    /**
     * 刪除既有的購物車資料
     *
     * @param int $user_id
     * @return void
     */
    public function deleteCartByUserId(int $user_id): void
    {
        DB::beginTransaction();

        try {
            /** @var array<int, \App\Models\Cart> 既有購物車資料 */
            $carts = $this->model
                ->where('carts.user_id', $user_id)
                ->get();

            foreach ($carts as $cart) {
                $cart->delete();
            }
        } catch (Exception $e) {
            report($e);
            DB::rollBack();

            throw $e;
        }

        DB::commit();
    }

    /**
     * 以帳號 ID 取得已儲存購物車內容
     *
     * @param int $user_id 帳號 ID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSavedCartByUserId(int $user_id): Collection
    {
        return $this->model
            ->where('carts.user_id', $user_id)
            ->get();
    }
}
