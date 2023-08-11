<?php

namespace App\Http\Controllers;

use App\Services\NewsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 資訊 Controller
 *
 * @OA\Tag(
 *   name="Information v1",
 *   description="資訊相關"
 * )
 */
class InformationController extends Controller
{
    /**
     * NewsService
     *
     * @var \App\Services\NewsService
     */
    protected $news_service;

    /**
     * 建構方法
     *
     * @param \App\Services\NewsService $news_service
     * @return void
     */
    public function __construct(NewsService $news_service)
    {
        $this->news_service = $news_service;
    }

    /**
     * 取得首頁最新消息 (3 行)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNews(): JsonResponse
    {
        $news = $this->news_service->getNewsList(3);

        return $this->response(null, $news);
    }
}
