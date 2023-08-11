<?php

namespace App\Repositories;

use App\Models\AvailablePlatform;
use App\Repositories\BaseRepository;

class AvailablePlatformRepository extends BaseRepository
{
    /**
     * Model 中文
     *
     * @return string
     */
    protected function name(): string
    {
        return '支援的平台';
    }

    /**
     * 建構方法
     *
     * @param \App\Models\AvailablePlatform $available_platform
     * @return void
     */
    public function __construct(AvailablePlatform $available_platform)
    {
        $this->model = $available_platform;
    }
}
