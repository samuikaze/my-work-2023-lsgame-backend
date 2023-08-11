<?php

namespace App\Repositories;

use App\Exceptions\EntityNotFoundException;
use App\Models\Reply;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ReplyRepository extends BaseRepository
{
    /**
     * Model 中文
     *
     * @return string
     */
    protected function name(): string
    {
        return '回應';
    }

    /**
     * 建構方法
     *
     * @param \App\Models\Reply $reply
     * @return void
     */
    public function __construct(Reply $reply)
    {
        $this->model = $reply;
    }

    /**
     * 取得指定文章底下的回應
     *
     * @param int $board_id 討論板 ID
     * @param int $post_id 文章 ID
     * @param int $start 起始筆數
     * @param int $rows 每頁筆數
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getReplies(int $board_id, int $post_id, int $start, int $rows): Collection
    {
        return $this->model
            ->select('replies.*')
            ->join('posts', 'replies.post_id', 'posts.id')
            ->where('posts.board_id', $board_id)
            ->where('posts.id', $post_id)
            ->skip($start)
            ->take($rows)
            ->get();
    }

    /**
     * 取得指定的回應
     *
     * @param int|null $board_id 討論板 ID
     * @param int|null $post_id 文章 ID
     * @param int|null $reply_id 回應 ID
     * @return \App\Models\Reply
     *
     * @throws \App\Exceptions\EntityNotFoundException
     */
    public function getReplyById(int $board_id, int $post_id, int $reply_id): Reply
    {
        $reply = $this->model
            ->select('replies.*')
            ->join('posts', 'replies.post_id', 'posts.id')
            ->where('posts.board_id', $board_id)
            ->where('posts.id', $post_id)
            ->where('replies.id', $reply_id)
            ->first();

        if (is_null($reply)) {
            throw new EntityNotFoundException('找不到該則回應');
        }

        return $reply;
    }

    /**
     * 取得回應總數
     *
     * @param int $board_id 討論板 ID
     * @param int $post_id 文章 ID
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getRepliesTotalRows(int $board_id, int $post_id): Model
    {
        return $this->model
            ->selectRaw('COUNT(DISTINCT replies.id) AS total_rows')
            ->join('posts', 'replies.post_id', 'posts.id')
            ->where('posts.board_id', $board_id)
            ->where('posts.id', $post_id)
            ->first();
    }
}
