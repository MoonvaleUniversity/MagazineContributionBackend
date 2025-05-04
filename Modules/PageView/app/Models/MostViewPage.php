<?php

namespace Modules\PageView\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Users\User\App\Models\User;

class MostViewPage extends Model
{
    protected $fillable = ['user_id', 'page_name', 'page_id', 'view_count'];
    const id = 'id';
    const page_id = 'page_id';
    const page_name = 'page_name';
    const user_id = 'user_id';
    const view_count = 'view_count';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }


}
