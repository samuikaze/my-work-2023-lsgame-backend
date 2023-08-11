<?php

namespace App\Http\Controllers;

use App\Services\SystemVariableService;
use Illuminate\Http\Request;

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
}
