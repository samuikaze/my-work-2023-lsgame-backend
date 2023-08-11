<?php

namespace App\Http\Controllers;

use App\Services\GoodService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

/**
 * 周邊商品 Controller
 *
 * @OA\Tag(
 *   name="Goods v1",
 *   description="周邊商品相關"
 * )
 */
class GoodController extends Controller
{
    /**
     * GoodService
     *
     * @var \App\Services\GoodService
     */
    protected $good_service;

    /**
     * 建構方法
     *
     * @param \App\Services\GoodService $good_service;
     * @return void
     */
    public function __construct(GoodService $good_service)
    {
        $this->good_service = $good_service;
    }

    /**
     * 取得商品列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGoodsList(): JsonResponse
    {
        $goods = $this->good_service->getGoodsList();

        return $this->response(null, $goods);
    }

    /**
     * 取得商品資料
     *
     * @param int|null $id 商品 ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGood(int $id = null): JsonResponse
    {
        if (is_null($id)) {
            $this->response('未知的商品 ID', null, self::HTTP_BAD_REQUEST);
        }

        try {
            $good = $this->good_service->getGood($id);
        } catch (InvalidArgumentException $e) {
            return $this->response($e->getMessage(), null, self::HTTP_BAD_REQUEST);
        }

        return $this->response(null, $good);
    }

    /**
     * 以 ID 取得購物車內商品詳細資料
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCartGoodsByIds(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'goods' => ['nullable', 'array'],
            'goods.*' => ['nullable', 'numeric'],
        ]);

        if ($validator->fails()) {
            return $this->response(
                '購物車商品格式不正確',
                null,
                self::HTTP_BAD_REQUEST
            );
        }

        $goods = $this->good_service->getCartGoodsByIds($request->input('goods'));

        return $this->response(null, $goods);
    }

    /**
     * 儲存購物車內容
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveCart(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'cart' => ['required', 'array'],
            'cart.*.id' => ['required', 'numeric'],
            'cart.*.quantity' => ['required', 'numeric', 'min:1'],
            'cart.*.price' => ['required', 'numeric', 'min:0'],
        ]);

        if ($validator->fails()) {
            return $this->response('購物車資料不正確', null, self::HTTP_BAD_REQUEST);
        }

        $this->good_service->saveCart($request->input('cart'));

        return $this->response();
    }

    /**
     * 清除購物車
     *
     * @param int|null $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetCart(int $user_id = null): JsonResponse
    {
        if (is_null($user_id)) {
            return $this->response('未知的使用者 ID', null, self::HTTP_BAD_REQUEST);
        }

        $this->good_service->resetCart($user_id);

        return $this->response();
    }

    /**
     * 以帳號 ID 取得已儲存購物車內容
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSavedCart(int $user_id = null)
    {
        if (is_null($user_id)) {
            return $this->response('未知的使用者 ID', null, self::HTTP_BAD_REQUEST);
        }

        $cart = $this->good_service->getSavedCart($user_id);

        return $this->response(null, $cart);
    }
}
