<?php

namespace Modules\AcademicYear\Services;

interface AcademicYearApiServiceInterface {
    public function get();

    public function getAll();

    public function save();

    public function update();

    public function delete();
}
