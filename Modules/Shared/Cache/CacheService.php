<?php

namespace Modules\Shared\Cache;

use App\Models\Cache as ModelCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CacheService implements CacheServiceInterface
{

    public function remember($baseKey, $expiry, $param, $callback)
    {
        try {
            $key = $baseKey . '_' . $this->getCacheKey($baseKey, $param);

            return Cache::remember($key, $expiry, $callback);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function clear($keys)
    {
        return ModelCache::where(function ($query) use ($keys) {
            foreach ($keys as $key) {
                $query->orWhere(ModelCache::key, 'like', '%' . $key . '%');
            }
        })->delete();
    }

    ///////////////////////////////////////////////////////////////////
    /// Private Functions
    ///////////////////////////////////////////////////////////////////

    //=================================================================
    // Other
    //=================================================================
    private function getCacheKey($baseKey, $param)
    {
        $key = $baseKey . '_' . implode('_', $this->processCacheKey($param));
        return md5($key);
    }

    private function processCacheKey($element)
    {
        $result = [];

        if (is_array($element)) {
            // Handle associative and indexed arrays
            foreach ($element as $key => $value) {
                if ($value === null) {
                    continue; // Skip null values
                }

                if (is_array($value)) {
                    // Recursively process nested arrays
                    $nestedResult = $this->processCacheKey($value);
                    foreach ($nestedResult as $nestedValue) {
                        $result[] = is_string($key) ? $key . "_" . $nestedValue : $nestedValue;
                    }
                } else {
                    $result[] = is_string($key) ? $key . "_" . $value : $value;
                }
            }
        } else {
            // Directly add non-array elements, skip null values
            if ($element !== null) {
                $result[] = $element;
            }
        }

        return $result;
    }
}
