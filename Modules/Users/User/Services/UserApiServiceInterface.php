<?php

namespace Modules\Users\User\Services;

interface UserApiServiceInterface
{
    /**
     * Retrieves a user with optional filtering, relations, and pagination.
     *
     * @param string|integer  $id        Filter users by id.
     * @param string|array  $relations   Related models to include.
     * @param array         $conds       Additional search conditions:
     *                                   - 'role' (string): Filter users by role.
     *                                   - 'email' (string): Filter users by email.
     *                                   - 'academic_year_id' (string): Filter users by academic year id.
     *                                   - 'faculty_id' (string): Filter users by faculty id.
     * @return \Modules\Users\User\App\Models\User
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
     *                                   - 'role' (string): Filter users by role.
     *                                   - 'email' (string): Filter users by email.
     *                                   - 'academic_year_id' (string): Filter users by academic year id.
     *                                   - 'faculty_id' (string): Filter users by faculty id.
     * @return \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null);

    /**
     * Create a new user.
     * @param array $userData
     * @return \Modules\Users\User\App\Models\User|null
     */
    public function create($userData);

    public function update($id, $userData);

    public function delete($id);

    public function getEmailById($id);
}
