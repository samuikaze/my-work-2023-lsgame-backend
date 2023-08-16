<?php

namespace App\Services;

use App\Repositories\SystemVariableRepository;
use Illuminate\Support\Collection;

class SystemVariableService
{
    /**
     * SystemVariableRepository
     *
     * @var \App\Repositories\SystemVariableRepository
     */
    protected $system_variable_repository;

    /**
     * 建構方法
     *
     * @param \App\Repositories\SystemVariableRepository $system_variable_repository
     * @return void
     */
    public function __construct(
        SystemVariableRepository $system_variable_repository
    ) {
        $this->system_variable_repository = $system_variable_repository;
    }

    /**
     * 取得系統變數值
     *
     * @param string $type 系統變數種類
     * @return \Illuminate\Support\Collection
     */
    public function getSystemVariables(string $type): Collection
    {
        return $this->system_variable_repository->getSystemVariables($type);
    }
}
