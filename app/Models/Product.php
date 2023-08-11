<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Product
 *
 * @property int $id
 * @property string $title 作品標題
 * @property string $visual_image 作品主視覺
 * @property string $description 作品描述
 * @property string|null $product_url 作品網址
 * @property string $type 作品種類
 * @property \Illuminate\Support\Carbon|null $release_at 作品發售日期
 * @property \Illuminate\Support\Carbon|null $created_at 作品建立日期
 * @property \Illuminate\Support\Carbon|null $updated_at 作品最後更新日期
 *
 * @property \Illuminate\Database\Eloquent\Collection $availablePlatforms 平台
 */
class Product extends Model
{
    use HasFactory;

    /**
     * 讀取的表格名稱
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'visual_image',
        'description',
        'product_url',
        'type',
        'release_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'release_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * 與 available_platforms 資料表的關聯
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function availablePlatforms()
    {
        return $this->hasMany(AvailablePlatform::class, 'product_id', 'id');
    }
}
