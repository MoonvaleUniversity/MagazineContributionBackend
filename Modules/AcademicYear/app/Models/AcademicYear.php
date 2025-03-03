<?php

namespace Modules\AcademicYear\App\Models;

use Modules\Users\User\App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\ClosureDate\App\Models\ClosureDate;
use App\Enums\Role;

class AcademicYear extends Model
{
    protected $fillable = ['year_name', 'version', 'created_by', 'updated_by'];

    const table = 'academic_years';
    const id = 'id';
    const year_name = 'year_name';
    const version = 'version';
    const created_by = 'created_by';
    const updated_by = 'updated_by';
    const created_at = 'created_at';
    const updated_at = 'updated_at';

    public function students()
    {
        return $this->hasMany(User::class, User::academic_year_id, self::id)
            ->whereHas('roles', function ($q) {
                $q->where('name', Role::STUDENT->label());
            });
    }

    public function closure_dates()
    {
        return $this->hasMany(ClosureDate::class, ClosureDate::academic_year_id, self::id);
    }
}
