<?php

namespace Modules\ClosureDate\Services;

interface ClosureDateApiInterface
{

    public function get();

    public function getAll();

    public function save();

    public function update();

    public function delete();
}
