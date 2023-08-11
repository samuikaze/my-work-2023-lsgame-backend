<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * News
 *
 * @property int $id
 * @property int $user_id 消息建立帳號
 * @property string $type 消息類型
 * @property string $title 消息標題
 * @property string $content 消息內容
 * @property \Illuminate\Support\Carbon|null $created_at 消息建立時間
 * @property \Illuminate\Support\Carbon|null $updated_at 消息最後更新時間
 * @property \Illuminate\Support\Carbon|null $deleted_at 消息刪除時間
 */
class News extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 讀取的表格名稱
     *
     * @var string
     */
    protected $table = 'news';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'content',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
