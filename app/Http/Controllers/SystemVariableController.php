<?php

namespace App\Http\Controllers;

use App\Services\SystemVariableService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * 系統變數
 *
 * @OA\Tag(
 *   name="SystemVariable v1",
 *   description="系統變數相關"
 * )
 */
class SystemVariableController extends Controller
{
    /**
     * SystemVariableService
     *
     * @var \App\Services\SystemVariableService
     */
    protected $system_variable_service;

    /**
     * 建構方法
     *
     * @param \App\Services\SystemVariableService $system_variable_service
     * @return void
     */
    public function __construct(
        SystemVariableService $system_variable_service
    ) {
        $this->system_variable_service = $system_variable_service;
    }

    /**
     * 取得系統變數值
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSystemVariables(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->response(
                '未指定系統變數種類',
                null,
                self::HTTP_BAD_REQUEST
            );
        }

        return $this->response(
            null,
            $this->system_variable_service->getSystemVariables($request->query('type'))
        );
    }
}
