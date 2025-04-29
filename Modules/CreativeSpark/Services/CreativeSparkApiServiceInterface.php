<?php

namespace Modules\CreativeSpark\Services;

interface CreativeSparkApiServiceInterface
{
    public function get($id = null, $conds = null);

    public function getAll($limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null);

    public function create($creativeSparkData, $imageFile);

    public function update($id, $creativeSparkData, $imageFile = null);

    public function delete($id);
}
