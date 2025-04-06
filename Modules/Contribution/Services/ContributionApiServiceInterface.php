<?php

namespace Modules\Contribution\Services;

interface ContributionApiServiceInterface
{
    /**
     * Retrieves a contribution with optional filtering, relations, and pagination.
     *
     * @param string|integer  $id        Filter contribution by id.
     * @param string|array  $relations   Related models to include.
     * @param array         $conds       Additional search conditions:
     *                                   - 'name' (string): Filter contributions by name.
     *                                   - 'user_id' (string): Filter contributions by user id.
     *                                   - 'user_id@@name' (string): Filter contributions by user's name.
     *                                   - 'user_id@@academic_year_id' (string): Filter contributions by user's academic year id.
     *                                   - 'closure_date_id' (string): Filter contributions by closure date it.
     *                                   - 'closure_date_id@@academic_year_id' (string): Filter contributions by closure date's academic year id.
     *                                   - 'is_selected_for_publication' (string): Filter contributions by selected for publication.
     * @return \Modules\Contribution\App\Models\Contribution
     */
    public function get($id = null, $relations = null, $conds = null);

    /**
     * Retrieves a list of contributions with optional filtering, relations, and pagination.
     *
     * @param string|array  $relations   Related models to include.
     * @param string|int    $limit       Number of records to retrieve.
     * @param string|int    $offset      Number of records to skip.
     * @param bool          $noPagination Whether to disable pagination (default: true).
     * @param int|null      $pagPerPage  Number of records per page (if paginated).
     * @param array         $conds       Additional search conditions:
     *                                   - 'name' (string): Filter contributions by name.
     *                                   - 'user_id' (string): Filter contributions by user id.
     *                                   - 'user_id@@name' (string): Filter contributions by user's name.
     *                                   - 'user_id@@academic_year_id' (string): Filter contributions by user's academic year id.
     *                                   - 'closure_date_id' (string): Filter contributions by closure date it.
     *                                   - 'closure_date_id@@academic_year_id' (string): Filter contributions by closure date's academic year id.
     *                                   - 'is_selected_for_publication' (string): Filter contributions by selected for publication.
     * @return \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null);

    public function create($contributionData, $wordFile, $imageFiles);

    public function update($id, $contributionData);

    public function downloadZip($id);

    public function delete($id);

    public function automatic();
}
