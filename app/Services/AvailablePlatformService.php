<?php

namespace App\Services;

use App\Repositories\AvailablePlatformRepository;
use Illuminate\Support\Collection;

class AvailablePlatformService
{
    /**
     * AvailablePlatformRepository
     *
     * @var \App\Repositories\AvailablePlatformRepository
     */
    protected $available_platform_repository;

    /**
     * 建構方法
     *
     * @param \App\Repositories\AvailablePlatformRepository $available_platform_repository
     * @return void
     */
    public function __construct(AvailablePlatformRepository $available_platform_repository)
    {
        $this->available_platform_repository = $available_platform_repository;
    }

    /**
     * 建立新的平台
     *
     * @param int $product_id 作品 ID
     * @param string[] $platforms 平台
     * @return \Illuminate\Support\Collection
     */
    public function createPlatforms(int $product_id, array $platforms): Collection
    {
        $platforms = array_map(function (string $platform) use ($product_id): array {
            return [
                'product_id' => $product_id,
                'platform' => $platform,
            ];
        }, $platforms);

        return $this->available_platform_repository->createMultipleRecords($platforms);
    }
}
