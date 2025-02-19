<?php

namespace Modules\Article\App\Models;

use App\Models\ArticleImage;
use Modules\Users\User\App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'version', 'created_by', 'updated_by', 'created_at', 'updated_at'];
    const table = 'articles';
    const id = 'id';
    const user_id = 'user_id';
    const title = 'title';
    const description = 'description';
    const version = 'version';
    const created_by = 'created_by';
    const updated_by = 'updated_by';
    const created_at = 'created_at';
    const updated_at = 'updated_at';

    public function admin()
    {
        return $this->belongsTo(User::class, self::user_id, User::id);
    }

    public function saved_users()
    {
        return $this->belongsToMany(User::class, 'saved_articles', 'article_id', 'user_id');
    }

    public function images()
    {
        return $this->hasMany(ArticleImage::class, ArticleImage::article_id, self::id);
    }
}
