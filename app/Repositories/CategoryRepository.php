<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository
{
    /**
     * Model 中文
     *
     * @return string
     */
    protected function name(): string
    {
        return '文章種類';
    }

    /**
     * 建構方法
     *
     * @param \App\Models\Category $category
     * @return void
     */
    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    /**
     * 取得所有文章種類
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPostTypes()
    {
        return $this->model->get();
    }
}
