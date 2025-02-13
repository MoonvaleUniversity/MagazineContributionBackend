<?php

namespace Modules\AcademicYear\Services;

interface AcademicYearApiInterface {
    public function get();

    public function getAll();

    public function save();

    public function update();

    public function delete();
}
