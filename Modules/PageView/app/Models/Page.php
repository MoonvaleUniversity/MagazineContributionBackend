<?php

namespace Modules\PageView\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use Modules\PageView\App\Models\MostViewPage;

class Page extends Model implements Viewable
{
    use HasFactory, InteractsWithViews;

    protected $fillable = ['name'];

    public function view()
    {
        return $this->hasMany(MostViewPage::class,'page_id');
    }
}




