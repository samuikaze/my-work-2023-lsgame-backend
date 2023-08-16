<?php

namespace App\Services;

use App\Repositories\NewsRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class NewsService
{
    /**
     * NewsRepository
     *
     * @var \App\Repositories\NewsRepository
     */
    protected $news_repository;

    /**
     * 建構方法
     *
     * @param \App\Repositories\NewsRepository $news_repository
     * @return void
     */
    public function __construct(NewsRepository $news_repository)
    {
        $this->news_repository = $news_repository;
    }

    /**
     * 取得新聞清單
     *
     * @param int|null $rows (10) 筆數
     * @param int|null $page (Optional) 頁碼
     * @param bool $need_deleted_news (Optional) 是否需要已被刪除的消息
     * @return array<string, int|\Illuminate\Database\Eloquent\Collection>
     *
     * @throws \App\Exceptions\EntityNotFoundException
     */
    public function getNewsList(int $rows = 10, int $page = null, bool $need_deleted_news = false): array
    {
        $page = is_null($page) ? 1 : $page;
        $page = ($page > 0) ? $page : 1;
        $page = ($page - 1) * $rows;

        $news_list = $this->news_repository->getNews($page, $rows, $need_deleted_news);
        $total_page = $this->news_repository->getTotalPages($rows);

        return [
            'newsList' => $news_list,
            'totalPages' => $total_page,
        ];
    }

    /**
     * 以 ID 取得消息內容
     *
     * @param int $id 消息 ID
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \App\Exceptions\EntityNotFoundException
     */
    public function getNewsById(int $id): Model
    {
        return $this->news_repository->find($id);
    }

    /**
     * 新增消息
     *
     * @param string $title 消息標題
     * @param string $type 消息種類
     * @param string $content 消息內容
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createNews(string $title, string $type, string $content): Model
    {
        $data = [
            'title' => $title,
            'type' => $type,
            'content' => $content,
        ];

        $news = $this->news_repository->create($data);

        return $news;
    }

    /**
     * 更新消息
     *
     * @param int $id 消息編號
     * @param string $title 消息標題
     * @param string $type 消息種類
     * @param string $content 消息內容
     * @return void
     */
    public function updateNews(int $id, string $title, string $type, string $content): void
    {
        $data = [
            'title' => $title,
            'type' => $type,
            'content' => $content,
        ];

        $this->news_repository->safeUpdate($id, $data);
    }

    /**
     * 刪除消息
     *
     * @param int $id 消息編號
     * @return void
     */
    public function deleteNews(int $id): void
    {
        $this->news_repository->delete($id);
    }
}
