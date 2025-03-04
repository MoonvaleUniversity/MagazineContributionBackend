<?php

namespace Modules\Users\Guest\Services;

interface GuestApiServiceInterface
{

    public function get();

    public function getAll();

    public function create();

    public function update();

    public function delete();
}
