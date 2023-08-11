<?php

namespace App\Http\Controllers;

use App\Services\AvailablePlatformService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AvailablePlatformController extends Controller
{
    /**
     * AvailablePlatformService
     *
     * @var \App\Services\AvailablePlatformService
     */
    protected $available_platform_service;

    /**
     * 建構方法
     *
     * @param \App\Services\AvailablePlatformService $available_platform_service
     * @return void
     */
    public function __construct(AvailablePlatformService $available_platform_service)
    {
        $this->available_platform_service = $available_platform_service;
    }

    /**
     * 建立新的平台
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPlatforms(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'productId' => ['required', 'numeric'],
            'productPlatforms' => ['required', 'array'],
            'productPlatforms.*' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->response(
                $validator->errors(),
                null,
                self::HTTP_BAD_REQUEST
            );
        }

        $platforms = $this->available_platform_service->createPlatforms(
            $request->input('productId'),
            $request->input('productPlatforms')
        );

        return $this->response($platforms);
    }
}
