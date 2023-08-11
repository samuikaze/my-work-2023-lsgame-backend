<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Reply;
use App\Repositories\BoardRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\PostRepository;
use App\Repositories\ReplyRepository;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class ForumService
{
    /**
     * BoardRepository
     *
     * @var \App\Repositories\BoardRepository
     */
    protected $board_repository;

    /**
     * PostRepository
     *
     * @var \App\Repositories\PostRepository
     */
    protected $post_repository;

    /**
     * ReplyRepository
     *
     * @var \App\Repositories\ReplyRepository
     */
    protected $reply_repository;

    /**
     * PostTypeRepository
     *
     * @var \App\Repositories\PostTypeRepository
     */
    protected $post_type_repository;

    /**
     * 建構方法
     *
     * @param \App\Repositories\BoardRepository $board_repository
     * @param \App\Repositories\PostRepository $post_repository
     * @param \App\Repositories\ReplyRepository $reply_repository
     * @param \App\Repositories\PostTypeRepository $post_type_repository
     * @return void
     */
    public function __construct(
        BoardRepository $board_repository,
        PostRepository $post_repository,
        ReplyRepository $reply_repository,
        CategoryRepository $post_type_repository
    ) {
        $this->board_repository = $board_repository;
        $this->post_repository = $post_repository;
        $this->reply_repository = $reply_repository;
        $this->post_type_repository = $post_type_repository;
    }

    /**
     * 取得討論板清單
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getForumBoardsList(): Collection
    {
        return $this->board_repository->getForumBoardsList();
    }

    /**
     * 取得討論版文章
     *
     * @param int $id 討論版 ID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBoardPosts(int $id): Collection
    {
        return $this->post_repository->getBoardPosts($id);
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
        return $this->post_repository->getPost($board_id, $post_id);
    }

    /**
     * 取得指定文章底下的回應
     *
     * @param int $board_id 討論板 ID
     * @param int $post_id 文章 ID
     * @param int $page 頁碼
     * @return array<string, \Illuminate\Database\Eloquent\Collection|int>
     */
    public function getReplies(int $board_id, int $post_id, int $page): array
    {
        /** @var int 每頁筆數 */
        $rows = 10;
        $start = ($page - 1) * $rows;

        $replies = $this->reply_repository->getReplies($board_id, $post_id, $start, $rows);
        $total_rows = $this->reply_repository->getRepliesTotalRows($board_id, $post_id);
        $total_rows = $total_rows->total_rows;

        $total_pages = 1;
        if ($total_rows > 0) {
            $total_pages = ceil($total_rows / 10);
        }

        return [
            'replies' => $replies,
            'totalPages' => $total_pages,
        ];
    }

    /**
     * 取得指定的回應
     *
     * @param int|null $board_id 討論板 ID
     * @param int|null $post_id 文章 ID
     * @param int|null $reply_id 回應 ID
     * @return \App\Models\Reply
     */
    public function getReplyById(int $board_id, int $post_id, int $reply_id): Reply
    {
        return $this->reply_repository->getReplyById($board_id, $post_id, $reply_id);
    }

    /**
     * 取得所有文章種類
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPostTypeList(): Collection
    {
        return $this->post_type_repository->getAllPostTypes();
    }

    /**
     * 發布新文章
     *
     * @param int $user_id 帳號 ID
     * @param int $board_id 討論板 ID
     * @param string $title 文章標題
     * @param int $type 文章分類
     * @param string $content 文章內文
     * @return void
     */
    public function createPost(int $user_id, int $board_id, string $title, int $category, string $content): void
    {
        $this->post_repository->create([
            'board_id' => $board_id,
            'post_user_id' => $user_id,
            'category_id' => $category,
            'title' => $title,
            'content' => $content,
            'status' => 1,
        ]);
    }

    /**
     * 發布新回應
     *
     * @param int $user_id 帳號 ID
     * @param int $board_id 討論板 ID
     * @param int $post_id 文章 ID
     * @param string $content 回應內文
     * @param string|null $title 回應標題
     * @return void
     */
    public function createReply(int $user_id, int $board_id, int $post_id, string $content, ?string $title): void
    {
        $this->reply_repository->create([
            'board_id' => $board_id,
            'post_user_id' => $user_id,
            'post_id' => $post_id,
            'title' => $title,
            'content' => $content,
            'status' => 1,
        ]);
    }

    /**
     * 編輯文章
     *
     * @param int $user_id 帳號 ID
     * @param int $board_id 討論板 ID
     * @param int $post_id 文章 ID
     * @param string $title 文章標題
     * @param int $category 文章分類
     * @param string $content 文章內容
     * @return void
     *
     * @throws \App\Exceptions\EntityNotFoundException
     * @throws \InvalidArgumentException
     */
    public function editPost(int $user_id, int $board_id, int $post_id, string $title, int $category, string $content): void
    {
        $post = $this->post_repository->find($post_id);
        if ($post->board_id != $board_id) {
            throw new InvalidArgumentException('討論板 ID 不正確');
        }

        if ($post->post_user_id != $user_id) {
            throw new InvalidArgumentException('無權編輯此文章');
        }

        $this->post_repository->safeUpdateRecord($post_id, [
            'category_id' => $category,
            'title' => $title,
            'content' => $content,
        ]);
    }

    /**
     * 編輯回應
     *
     * @param int $user_id
     * @param int $board_id
     * @param int $post_id
     * @param int $reply_id
     * @param string $content
     * @param string|null $title
     * @return void
     *
     * @throws \App\Exceptions\EntityNotFoundException
     * @throws \InvalidArgumentException
     */
    public function editReply(int $user_id, int $board_id, int $post_id, int $reply_id, string $content, ?string $title): void
    {
        $post = $this->post_repository->find($post_id);
        if ($post->board_id != $board_id) {
            throw new InvalidArgumentException('討論板 ID 不正確');
        }

        $reply = $this->reply_repository->find($reply_id);
        if ($reply->post_id != $post_id) {
            throw new InvalidArgumentException('文章 ID 不正確');
        }

        if ($reply->post_user_id != $user_id) {
            throw new InvalidArgumentException('無權編輯此文章');
        }

        $this->reply_repository->safeUpdateRecord($reply_id, [
            'title' => $title,
            'content' => $content,
        ]);
    }
}
