<?php

namespace Modules\Users\Manager\Services;

interface ManagerApiServiceInterface
{

    public function get();

    public function getAll();

    public function create();

    public function update();

    public function delete();
}
