<?php

namespace Modules\Users\Manager\Services;

interface ManagerApiInterface
{

    public function get();

    public function getAll();

    public function save();

    public function update();

    public function delete();
}
