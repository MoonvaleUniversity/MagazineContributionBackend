<?php

namespace Modules\BrowserTrack\Services;

use Illuminate\Http\Request;
use Modules\PageView\App\Models\Page;

interface BrowserApiServiceInterface {
    public function get($id = null, $relations = null, $conds = null);

    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null);

    public function create($pageViewData);

    public function update();

    public function delete();
}
