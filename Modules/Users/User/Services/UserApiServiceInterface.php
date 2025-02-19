<?php

namespace Modules\Users\User\Services;

interface UserApiServiceInterface
{
    public function get($id = null, $relations = null);

    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null);

    public function create();

    public function update();

    public function delete();
}
