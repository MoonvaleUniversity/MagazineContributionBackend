<?php

namespace Modules\Users\Coordinator\Services;

interface CoordinatorApiInterface
{

    public function get();

    public function getAll();

    public function save();

    public function update();

    public function delete();
}
