<?php

namespace Modules\ClosureDate\Services;

interface ClosureDateApiServiceInterface
{
    /**
     * Retrieves a closure dates with optional filtering, relations, and pagination.
     *
     * @param string|integer  $id        Filter users by id.
     * @param string|array  $relations   Related models to include.
     * @param array         $conds       Additional search conditions:
     *                                   - 'academic_year_id' (string): Filter closure dates by academic year id.
     *                                   - 'academic_year_id@@year_name' (string): Filter closure dates by acadmic year name.
     * @return \Modules\ClosureDate\App\Models\ClosureDate
     */
    public function get($id = null, $relations = null, $conds = null);

    /**
     * Retrieves a list of users with optional filtering, relations, and pagination.
     *
     * @param string|array  $relations   Related models to include.
     * @param string|int    $limit       Number of records to retrieve.
     * @param string|int    $offset      Number of records to skip.
     * @param bool          $noPagination Whether to disable pagination (default: true).
     * @param int|null      $pagPerPage  Number of records per page (if paginated).
     * @param array         $conds       Additional search conditions:
     *                                   - 'academic_year_id' (string): Filter closure dates by academic year id.
     *                                   - 'academic_year_id@@year_name' (string): Filter closure dates by acadmic year name.
     * @return \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null);
    public function create($closureDateData);
    public function update($id, $closureDateData);
    public function delete($id);
}
