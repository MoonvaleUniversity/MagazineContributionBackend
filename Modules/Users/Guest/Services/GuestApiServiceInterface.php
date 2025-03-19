<?php

namespace Modules\Users\Guest\Services;

interface GuestApiServiceInterface
{
    /**
     * Retrieves a coordinator with optional filtering, relations, and pagination.
     *
     * @param string|integer  $id        Filter guests by id.
     * @param string|array  $relations   Related models to include.
     * @param array         $conds       Additional search conditions:
     *                                   - 'email' (string): Filter guests by email.
     *                                   - 'academic_year_id' (string): Filter guests by academic year id.
     *                                   - 'faculty_id' (string): Filter guests by faculty id.
     * @return \Modules\Users\User\App\Models\User
     */
    public function get($id = null, $relations = null, $conds = null);

    /**
     * Retrieves a list of guests with optional filtering, relations, and pagination.
     *
     * @param string|array  $relations   Related models to include.
     * @param string|int    $limit       Number of records to retrieve.
     * @param string|int    $offset      Number of records to skip.
     * @param bool          $noPagination Whether to disable pagination (default: true).
     * @param int|null      $pagPerPage  Number of records per page (if paginated).
     * @param array         $conds       Additional search conditions:
     *                                   - 'email' (string): Filter guests by email.
     *                                   - 'academic_year_id' (string): Filter guests by academic year id.
     *                                   - 'faculty_id' (string): Filter guests by faculty id.
     * @return \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null);

    /**
     * Create a new guest.
     * @param array $guestData
     * @return \Modules\Users\User\App\Models\User|null
     */
    public function create(array $guestData);

    /**
     * Update an existing guest.
     * @param int $id
     * @param array $guestData
     * @return \Modules\Users\User\App\Models\User|null
     */
    public function update(int $id,array $guestData);

    /**
     * Update an existing coordinator.
     * @param int $id
     * @return string name
     */
    public function delete(int $id);
}
