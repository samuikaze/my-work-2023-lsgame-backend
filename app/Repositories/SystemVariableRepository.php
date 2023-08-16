<?php

namespace App\Repositories;

use App\Models\SystemVariable;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;

class SystemVariableRepository extends BaseRepository
{
    /**
     * Model 中文
     *
     * @return string
     */
    protected function name(): string
    {
        return '系統變數';
    }

    /**
     * 建構方法
     *
     * @param \App\Models\SystemVariable $system_variable
     * @return void
     */
    public function __construct(SystemVariable $system_variable)
    {
        $this->model = $system_variable;
    }

    /**
     * 取得系統變數值
     *
     * @param string $type 系統變數種類
     * @return \Illuminate\Support\Collection
     */
    public function getSystemVariables(string $type): Collection
    {
        return $this->model
            ->where('type', $type)
            ->get();
    }
}
