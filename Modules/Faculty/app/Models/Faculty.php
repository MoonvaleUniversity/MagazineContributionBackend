<?php

namespace Modules\Faculty\App\Models;

use App\Enums\Role;
use App\Models\Traits\Audit;
use App\Models\Traits\Version;
use Modules\Users\User\App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use Audit, Version;
    protected $fillable = ['name', 'image_url', 'version', 'created_by', 'updated_by'];

    const table = 'faculties';
    const id = 'id';
    const name = 'name';
    const image_url = 'image_url';
    const version = 'version';
    const created_by = 'created_by';
    const updated_by = 'updated_by';

    public function coordinators()
    {
        return $this->hasMany(User::class, User::faculty_id, self::id)
            ->whereHas('roles', function ($q) {
                $q->where('name', Role::MARKETING_COORDINATOR->label());
            });;
    }

    public function students()
    {
        return $this->hasMany(User::class, User::faculty_id, self::id)
            ->whereHas('roles', function ($q) {
                $q->where('name', Role::STUDENT->label());
            });
    }

    public function users()
    {
        return $this->hasMany(User::class, User::faculty_id, self::id);
    }
}
