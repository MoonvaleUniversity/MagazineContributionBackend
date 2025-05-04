<?php

namespace Modules\BrowserTrack\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Users\User\App\Models\User;

class BrowserTrack extends Model
{
    protected $fillable = ['user_id', 'browser_name', 'browser_version', 'os'];
    const id = 'id';
    const user_id = 'user_id';
    const browser_name = 'browser_name';
    const browser_version = 'browser_version';
    const os = 'os';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }


}
