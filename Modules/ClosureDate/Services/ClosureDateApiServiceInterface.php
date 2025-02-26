<?php

namespace Modules\ClosureDate\Services;

interface ClosureDateApiServiceInterface
{

    public function get($id);
    public function getAll();
    public function create($data);
    public function update( $id, $data);
    public function delete( $id);
}
