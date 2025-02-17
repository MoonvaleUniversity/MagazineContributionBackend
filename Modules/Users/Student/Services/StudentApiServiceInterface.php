<?php

namespace Modules\Users\Student\Services;

interface StudentApiServiceInterface
{

    public function get();

    public function getAll();

    public function save();

    public function update();

    public function delete();
}
