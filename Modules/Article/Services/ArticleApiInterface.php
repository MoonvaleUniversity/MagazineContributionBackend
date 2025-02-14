<?php

namespace Modules\Article\Services;

interface ArticleApiInterface
{

    public function get();

    public function getAll();

    public function save();

    public function update();

    public function delete();
}
