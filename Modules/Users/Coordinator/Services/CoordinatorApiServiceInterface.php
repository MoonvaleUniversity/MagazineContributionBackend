<?php

namespace Modules\Users\Coordinator\Services;

interface CoordinatorApiServiceInterface
{
    /**
     * Retrieves a coordinator with optional filtering, relations, and pagination.
     *
     * @param string|integer  $id        Filter coordinators by id.
     * @param string|array  $relations   Related models to include.
     * @param array         $conds       Additional search conditions:
     *                                   - 'email' (string): Filter coordinators by email.
     *                                   - 'academic_year_id' (string): Filter coordinators by academic year id.
     *                                   - 'faculty_id' (string): Filter coordinators by faculty id.
     * @return \Modules\Users\User\App\Models\User
     */
    public function get($id = null, $relations = null, $conds = null);

    /**
     * Retrieves a list of coordinators with optional filtering, relations, and pagination.
     *
     * @param string|array  $relations   Related models to include.
     * @param string|int    $limit       Number of records to retrieve.
     * @param string|int    $offset      Number of records to skip.
     * @param bool          $noPagination Whether to disable pagination (default: true).
     * @param int|null      $pagPerPage  Number of records per page (if paginated).
     * @param array         $conds       Additional search conditions:
     *                                   - 'email' (string): Filter coordinators by email.
     *                                   - 'academic_year_id' (string): Filter coordinators by academic year id.
     *                                   - 'faculty_id' (string): Filter coordinators by faculty id.
     * @return \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null);

    /**
     * Create a new coordinator.
     * @param array $coordinatorData
     * @return \Modules\Users\User\App\Models\User|null
     */
    public function create(array $coordinatorData);

    /**
     * Update an existing coordinator.
     * @param int $id
     * @param array $coordinatorData
     * @return \Modules\Users\User\App\Models\User|null
     */
    public function update(int $id,array $coordinatorData);

    /**
     * Update an existing coordinator.
     * @param int $id
     * @return string name
     */
    public function delete(int $id);
}
