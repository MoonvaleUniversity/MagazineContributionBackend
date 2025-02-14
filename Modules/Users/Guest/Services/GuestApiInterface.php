<?php

namespace Modules\Users\Guest\Services;

interface GuestApiInterface
{

    public function get();

    public function getAll();

    public function save();

    public function update();

    public function delete();
}
