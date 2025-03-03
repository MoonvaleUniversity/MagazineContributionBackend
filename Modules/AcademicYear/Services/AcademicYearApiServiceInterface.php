<?php

namespace Modules\AcademicYear\Services;

interface AcademicYearApiServiceInterface {
    public function get($id = null, $relations = null, $conds = null);

    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null);

    public function create($academicYearData);

    public function update($id, $academicYearData);

    public function delete($id);
}
