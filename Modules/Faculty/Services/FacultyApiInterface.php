<?php

namespace Modules\Faculty\Services;

interface FacultyApiInterface
{

    public function get();

    public function getAll();

    public function save();

    public function update();

    public function delete();
}
