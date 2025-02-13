<?php

namespace Modules\Contribution\App\Models;

use Illuminate\Database\Eloquent\Model;

class ContributionImage extends Model
{
    protected $fillable = ['contribution_id', 'image_url', 'created_at', 'updated_at'];

    const table = 'contribution_images';
    const id = 'id';
    const contribution_id = 'contribution_id';
    const image_url = 'image_url';
    const created_at = 'created_at';
    const updated_at = 'updated_at';

    public function contribution()
    {
        return $this->belongsTo(Contribution::class, self::contribution_id, Contribution::id);
    }
}
