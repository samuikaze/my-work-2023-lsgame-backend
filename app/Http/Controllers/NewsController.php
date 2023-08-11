<?php

namespace App\Http\Controllers;

use App\Exceptions\EntityNotFoundException;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * 最新消息 Controller
 *
 * @OA\Tag(
 *   name="News v1",
 *   description="最新消息相關"
 * )
 */
class NewsController extends Controller
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
     * 取得最新消息清單
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNewsList(Request $request): JsonResponse
    {
        try {
            $list = $this->news_service->getNewsList(10, $request->input('page'));
        } catch (EntityNotFoundException $e) {
            return $this->response($e->getMessage(), null, self::HTTP_BAD_REQUEST);
        }

        return $this->response(null, $list);
    }

    /**
     * 取得消息內容
     *
     * @param int|null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNews(int $id = null): JsonResponse
    {
        if (is_null($id)) {
            return $this->response('未知的消息 ID', null, self::HTTP_BAD_REQUEST);
        }

        try {
            $news = $this->news_service->getNewsById($id);
        } catch (EntityNotFoundException $e) {
            return $this->response('查無該筆消息', null, self::HTTP_BAD_REQUEST);
        }

        return $this->response(null, $news);
    }

    /**
     * 新增消息
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createNews(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'newsTitle' => ['required', 'string'],
            'newsType' => ['required', 'string'],
            'newsContent' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->response($validator->errors(), null, self::HTTP_BAD_REQUEST);
        }

        $news = $this->news_service->createNews(
            $request->input('newsTitle'),
            $request->input('newsType'),
            $request->input('newsContent')
        );

        return $this->response(null, $news->id);
    }

    /**
     * 更新消息
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateNews(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'newsId' => ['required', 'numeric'],
            'newsTitle' => ['required', 'string'],
            'newsType' => ['required', 'string'],
            'newsContent' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->response($validator->errors(), null, self::HTTP_BAD_REQUEST);
        }

        $this->news_service->updateNews(
            $request->input('newsId'),
            $request->input('newsTitle'),
            $request->input('newsType'),
            $request->input('newsContent'),
        );

        return $this->response();
    }

    /**
     * 刪除消息
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNews(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'newsId' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return $this->response($validator->errors(), null, self::HTTP_BAD_REQUEST);
        }

        $this->news_service->deleteNews($request->input('newsId'));
    }
}
