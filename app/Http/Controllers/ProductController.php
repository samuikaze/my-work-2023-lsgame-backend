<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * 作品 Controller
 *
 * @OA\Tag(
 *   name="Product v1",
 *   description="作品相關"
 * )
 */
class ProductController extends Controller
{
    /**
     * ProductService
     *
     * @var \App\Services\ProductService
     */
    protected $product_service;

    /**
     * 建構方法
     *
     * @param \App\Services\ProductService $product_service
     * @return void
     */
    public function __construct(ProductService $product_service)
    {
        $this->product_service = $product_service;
    }

    /**
     * 取得作品清單
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductList(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'page' => ['nullable', 'numeric'],
        ]);

        if ($validator->fails()) {
            return $this->response(
                '指定的頁碼不符合資料格式',
                null,
                self::HTTP_BAD_REQUEST
            );
        }

        $page = 1;
        if ($request->has('page') && ! is_null($request->input('page'))) {
            $page = $request->input('page');
        }

        $products = $this->product_service->getProductList($page);

        return $this->response(null, $products);
    }

    /**
     * 建立新作品
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createNewProduct(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'productTitle' => ['required', 'string'],
            'productVisualImage' => ['required', 'string'],
            'productDesccription' => ['required', 'string'],
            'productUrl' => ['nullable', 'string'],
            'productType' => ['required', 'string'],
            'releaseDate' => ['nullable', 'date'],
        ]);

        if ($validator->fails()) {
            return $this->response(
                $validator->errors(),
                [],
                self::HTTP_BAD_REQUEST
            );
        }

        $new_product = $this->product_service->createNewProduct(
            $request->input('productTitle'),
            $request->input('productVisualImage'),
            $request->input('productDesccription'),
            $request->input('productType'),
            $request->input('releaseDate'),
            $request->input('productUrl')
        );

        return $this->response(null, $new_product);
    }
}
