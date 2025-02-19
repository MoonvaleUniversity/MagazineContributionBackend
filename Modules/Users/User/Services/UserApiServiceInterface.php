<?php

namespace Modules\Users\User\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserApiServiceInterface
{
    /**
     * Retrieves a list of users with optional filtering, relations, and pagination.
     *
     * @param string|integer  $id        Filter users by id.
     * @param string|array  $relations   Related models to include.
     * @param array         $conds       Additional search conditions:
     *                                   - 'role' (string): Filter users by role.
     *                                   - 'email' (string): Filter users by email.
     * @return LengthAwarePaginator|Collection
     */
    public function get($id = null, $relations = null, array $conds = null);

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
     * @return LengthAwarePaginator|Collection
     */
    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null);

    public function create();

    public function update();

    public function delete();
}
