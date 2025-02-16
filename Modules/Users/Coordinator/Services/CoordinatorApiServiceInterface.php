<?php

namespace Modules\Users\Coordinator\Services;

interface CoordinatorApiServiceInterface
{

    public function get();

    public function getAll();

    public function save();

    public function update();

    public function delete();
}
