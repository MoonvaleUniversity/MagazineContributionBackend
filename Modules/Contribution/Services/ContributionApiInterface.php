<?php

namespace Modules\Contribution\Services;

interface ContributionApiInterface
{

    public function get();

    public function getAll();

    public function save();

    public function update();

    public function delete();
}
