<?php

namespace Modules\ClosureDate\Services;

use Modules\ClosureDate\App\Models\ClosureDate;

interface ClosureDateApiServiceInterface
{

    public function getById($id);
    public function getAll();
    public function create($data);
    public function update( $id, $data);
    public function delete( $id);
}
