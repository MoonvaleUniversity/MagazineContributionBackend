<?php

namespace Modules\CreativeSpark\App\Models;

use Illuminate\Database\Eloquent\Model;

class CreativeSpark extends Model
{
    protected $fillable = ['title', 'image_url', 'content', 'version', 'created_by', 'updated_by'];

    const table = 'creative_sparks';
    const id = 'id';
    const title = 'title';
    const content  = 'content';
    const image_url = 'image_url';
    const version = 'version';
    const created_by = 'created_by';
    const updated_by = 'updated_by';
    const created_at = 'created_at';
    const updated_at = 'updated_at';
}
