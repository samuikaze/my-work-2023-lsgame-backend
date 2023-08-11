<?php

namespace App\Services;

use App\Repositories\SystemVariableRepository;

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
}
