<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Article\App\Models\Article;

class ArticleImage extends Model
{
    protected $fillable = ['id', 'article_id', 'image_url'];
    const table = 'article_images';
    const id = 'id';
    const article_id = 'article_id';
    const image_url = 'image_url';
    const created_at = 'created_at';
    const updated_at = 'updated_at';

    public function article()
    {
        return $this->belongsTo(Article::class, self::article_id, Article::id);
    }
}
