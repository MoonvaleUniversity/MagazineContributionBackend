<?php

namespace Modules\Faculty\Services;

interface FacultyApiServiceInterface
{
    /**
     * Retrieves a faculty with optional filtering, relations, and pagination.
     *
     * @param string|integer  $id        Filter faculty by id.
     * @param string|array  $relations   Related models to include.
     * @param array         $conds       Additional search conditions:
     *                                   - 'name' (string): Filter faculties by name.
     *                                   - 'student@@id' (string): Filter faculties by student's id.
     *                                   - 'user@@id' (string): Filter faculties by user's id.
     * @return \Modules\Faculty\App\Models\Faculty
     */
        public function get($id = null, $relations = null, $conds = null);

    /**
     * Retrieves a list of faculties with optional filtering, relations, and pagination.
     *
     * @param string|array  $relations   Related models to include.
     * @param string|int    $limit       Number of records to retrieve.
     * @param string|int    $offset      Number of records to skip.
     * @param bool          $noPagination Whether to disable pagination (default: true).
     * @param int|null      $pagPerPage  Number of records per page (if paginated).
     * @param array         $conds       Additional search conditions:
     *                                   - 'name' (string): Filter faculties by name.
     *                                   - 'student@@id' (string): Filter faculties by student's id.
     *                                   - 'user@@id' (string): Filter faculties by user's id.
     * @return \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null);

    public function create($facultyData, $imageFile);

    public function update($id, $facultyData);

    public function delete($id);
}
