<?php

namespace Modules\Shared\Cache;

interface CacheServiceInterface {
    public function remember($baseKey, $expiry, $param, $callback);

    public function clear($keys);
}
