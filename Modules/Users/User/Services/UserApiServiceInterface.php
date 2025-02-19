<?php

namespace Modules\Users\User\Services;

interface UserApiServiceInterface
{
    public function get($id = null, $relations = null);

    public function getAll();

    public function create();

    public function update();

    public function delete();
}
