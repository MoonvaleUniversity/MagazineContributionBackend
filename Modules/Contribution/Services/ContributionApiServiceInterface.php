<?php

namespace Modules\Contribution\Services;

interface ContributionApiServiceInterface
{

    public function get();

    public function getAll();

    public function save();

    public function update();

    public function delete();
}
