<?php

namespace Modules\ClosureDate\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\AcademicYear\App\Models\AcademicYear;

class ClosureDate extends Model
{
    protected $fillable = ['closure_date', 'final_closure_date', 'academic_year_id', 'version', 'created_by', 'updated_by'];

    const table = 'closure_dates';
    const closure_date = 'closure_date';
    const final_closure_date = 'final_closure_date';
    const academic_year_id = 'academic_year_id';
    const version = 'version';
    const created_by = 'created_by';
    const updated_by = 'updated_by';

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class,self::academic_year_id,AcademicYear::id);
    }
}
