<?php

namespace App\Repositories;

use App\Models\Good;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use InvalidArgumentException;

class GoodRepository extends BaseRepository
{
    /**
     * Model 中文
     *
     * @return string
     */
    protected function name(): string
    {
        return '商品';
    }

    /**
     * 建構方法
     *
     * @param \App\Models\Good $good
     * @return void
     */
    public function __construct(Good $good)
    {
        $this->model = $good;
    }

    /**
     * 取得商品列表
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getGoodsList(): Collection
    {
        return $this->model
            ->select(
                'goods.*'
            )
            ->get();
    }

    /**
     * 取得商品資料
     *
     * @param int $id 商品 ID
     * @return \App\Models\Good
     *
     * @throws \InvalidArgumentException
     */
    public function getGood(int $id): Good
    {
        $good = $this->model
            ->where('goods.id', $id)
            ->first();

        if (is_null($good)) {
            throw new InvalidArgumentException('找不到該商品');
        }

        return $good;
    }

    /**
     * 以 ID 取得商品詳細資料
     *
     * @param array<int, int> $goods 商品 IDs
     * @return \Illuminate\Support\Collection<Good>
     */
    public function getCartGoodsByIds(array $goods): SupportCollection
    {
        /** @var array<int, array<int, int>> */
        $chunked_goods = array_chunk($goods, 1000);

        $result = collect();
        foreach ($chunked_goods as $goods) {
            $good_details = $this->model
                ->whereIn('goods.id', $goods)
                ->get();

            $result = $result->merge($good_details);
        }

        return $result;
    }
}
