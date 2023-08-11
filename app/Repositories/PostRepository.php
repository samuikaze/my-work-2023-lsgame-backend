<?php

namespace App\Repositories;

use App\Exceptions\EntityNotFoundException;
use App\Models\Post;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class PostRepository extends BaseRepository
{
    /**
     * Model 中文
     *
     * @return string
     */
    protected function name(): string
    {
        return '文章';
    }

    /**
     * 建構方法
     *
     * @param \App\Models\Post $post
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->model = $post;
    }

    /**
     * 取得貼文清單
     *
     * @param int $board_id 討論板 ID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBoardPosts(int $board_id)
    {
        $replies = DB::table('replies')
            ->select('replies.*')
            ->orderBy('replies.created_at', 'desc');

        return $this->model
            ->select(
                'posts.id',
                'posts.board_id',
                'posts.post_user_id',
                'posts.category_id',
                'categories.name AS category',
                'posts.title',
                'posts.content',
                'posts.status',
                'posts.created_at',
                'posts.updated_at',
                'posts.deleted_at',
                DB::raw('COUNT(DISTINCT replies.id) AS replies_quantity'),
                'replies.created_at AS last_operation_at'
            )
            ->join('categories', 'posts.category_id', 'categories.id')
            ->leftJoinSub($replies, 'replies', 'replies.post_id', '=', 'posts.id')
            ->where('board_id', $board_id)
            ->groupBy('posts.id')
            ->orderBy('posts.updated_at', 'desc')
            ->get();
    }

    /**
     * 取得文章本體
     *
     * @param int $board_id 討論板 ID
     * @param int $post_id 文章 ID
     * @return \App\Models\Post
     *
     * @throws \App\Exceptions\EntityNotFoundException
     */
    public function getPost(int $board_id, int $post_id): Post
    {
        $post = $this->model
            ->select(
                'posts.*',
                'categories.name AS category'
            )
            ->join('categories', 'posts.category_id', 'categories.id')
            ->where('posts.board_id', $board_id)
            ->where('posts.id', $post_id)
            ->first();

        if (is_null($post)) {
            throw new EntityNotFoundException('找不到該文章');
        }

        return $post;
    }
}
