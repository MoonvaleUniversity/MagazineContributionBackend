<?php

namespace Modules\Faculty\Services;

interface FacultyApiServiceInterface
{

    public function get();

    public function getAll();

    public function save();

    public function update();

    public function delete();
}
