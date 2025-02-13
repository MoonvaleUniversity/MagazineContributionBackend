<?php

namespace Modules\Users\Admin\Services;

interface AdminApiInterface
{

    public function get();

    public function getAll();

    public function save();

    public function update();

    public function delete();
}
