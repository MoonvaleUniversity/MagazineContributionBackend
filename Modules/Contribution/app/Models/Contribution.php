<?php

namespace Modules\Contribution\App\Models;

use Modules\Users\User\App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\ClosureDate\App\Models\ClosureDate;

class Contribution extends Model
{
    protected $fillable = ['name', 'user_id', 'closure_date_id', 'doc_url', 'is_selected_for_publication', 'version', 'created_by', 'updated_by'];

    const table = 'contributions';
    const id = 'id';
    const name = 'name';
    const user_id = 'user_id';
    const closure_date_id = 'closure_date_id';
    const doc_url = 'doc_url';
    const is_selected_for_publication = 'is_selected_for_publication';
    const version = 'version';
    const created_by = 'created_by';
    const updated_by = 'updated_by';
    const created_at = 'created_at';
    const updated_at = 'updated_at';

    public function images()
    {
        return $this->hasMany(ContributionImage::class, ContributionImage::contribution_id, self::id);
    }

    public function user_comments()
    {
        return $this->belongsToMany(User::class, 'comments', 'contribution_id', self::user_id)->withPivot(['content', 'created_at', 'updated_at']);
    }

    public function user_votes()
    {
        return $this->belongsToMany(User::class, 'votes', 'contribution_id', 'user_id')->withPivot(['type']);
    }

    public function saved_users()
    {
        return $this->belongsToMany(User::class, 'saved_contributions', 'contribution_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, self::user_id);
    }

    public function closure_date()
    {
        return $this->belongsTo(ClosureDate::class, self::closure_date_id);
    }
}
