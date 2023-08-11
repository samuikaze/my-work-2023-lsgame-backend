<?php

namespace App\Repositories;

use App\Exceptions\EntityNotFoundException;
use App\Models\News;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class NewsRepository extends BaseRepository
{
    /**
     * Model 中文
     *
     * @return string
     */
    protected function name(): string
    {
        return '最新消息';
    }

    /**
     * 建構方法
     *
     * @param \App\Models\News $news
     * @return void
     */
    public function __construct(News $news)
    {
        $this->model = $news;
    }

    /**
     * 取得最新消息
     *
     * @param int $start 起始行數
     * @param int $rows 取得筆數
     * @param bool $need_deleted_news (Optional) 是否需要已被刪除的消息
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNews(int $start, int $rows, bool $need_deleted_news = false): Collection
    {
        $builder = $this->model
            ->select('news.*')
            ->orderBy('news.created_at', 'desc')
            ->skip($start)
            ->take($rows);

        if ($need_deleted_news) {
            $builder = $builder
                ->addSelect(
                    DB::raw('(CASE WHEN news.deleted_at IS NOT NULL THEN 1 ELSE 0 END) AS is_deleted')
                )
                ->withTrashed();
        }

        return $builder->get();
    }

    /**
     * 取得總頁數
     *
     * @param int $rows 單頁筆數
     * @return int
     *
     * @throws \App\Exceptions\EntityNotFoundException
     */
    public function getTotalPages(int $rows): int
    {
        $total_rows = $this->model
            ->selectRaw('COUNT(news.id) AS total_rows')
            ->distinct()
            ->first();

        if (is_null($total_rows)) {
            throw new EntityNotFoundException('取得頁碼失敗');
        }

        /** @var int */
        $total_rows = $total_rows->total_rows;

        $pages = ceil($total_rows / $rows);

        return $pages;
    }
}
