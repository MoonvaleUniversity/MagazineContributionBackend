<?php

namespace Modules\Users\Admin\Services;

interface AdminApiServiceInterface
{
    /**
     * Retrieves a admin with optional filtering, relations, and pagination.
     *
     * @param string|integer  $id        Filter admins by id.
     * @param string|array  $relations   Related models to include.
     * @param array         $conds       Additional search conditions:
     *                                   - 'email' (string): Filter admins by email.
     *                                   - 'academic_year_id' (string): Filter admins by academic year id.
     *                                   - 'faculty_id' (string): Filter admins by faculty id.
     * @return \Modules\Users\User\App\Models\User
     */
    public function get($id = null, $relations = null, $conds = null);

    /**
     * Retrieves a list of admins with optional filtering, relations, and pagination.
     *
     * @param string|array  $relations   Related models to include.
     * @param string|int    $limit       Number of records to retrieve.
     * @param string|int    $offset      Number of records to skip.
     * @param bool          $noPagination Whether to disable pagination (default: true).
     * @param int|null      $pagPerPage  Number of records per page (if paginated).
     * @param array         $conds       Additional search conditions:
     *                                   - 'email' (string): Filter admins by email.
     *                                   - 'academic_year_id' (string): Filter admins by academic year id.
     *                                   - 'faculty_id' (string): Filter admins by faculty id.
     * @return \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null);

    /**
     * Create a new admin.
     * @param array $adminData
     * @return \Modules\Users\User\App\Models\User|null
     */
    public function create(array $adminData);

    /**
     * Update an existing admin.
     * @param int $id
     * @param array $adminData
     * @return \Modules\Users\User\App\Models\User|null
     */
    public function update(int $id, array $adminData);

    /**
     * Update an existing admin.
     * @param int $id
     * @return string name
     */
    public function delete(int $id);
}
