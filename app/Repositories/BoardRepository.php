<?php

namespace App\Repositories;

use App\Models\Board;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class BoardRepository extends BaseRepository
{
    /**
     * Model 中文
     *
     * @return string
     */
    protected function name(): string
    {
        return '討論版';
    }

    /**
     * 建構方法
     *
     * @param \App\Models\Board $board
     * @return void
     */
    public function __construct(Board $board)
    {
        $this->model = $board;
    }

    /**
     * 取得討論板清單
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getForumBoardsList(): Collection
    {
        return $this->model->get();
    }
}
