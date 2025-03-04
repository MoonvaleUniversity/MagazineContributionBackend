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
        $page = null; // Default

        // Handle case where $element is an array and has indexes 3 and 4
        if (is_array($element) && isset($element[3], $element[4])) {
            $noPagination = $element[3];
            $pagPerPage = $element[4];

            $page = (empty($noPagination) || $pagPerPage)
                ? request()->get('page', 1)
                : null;
        }

        if (is_array($element)) {
            foreach ($element as $key => $value) {
                if ($value === null) {
                    continue; // Skip null values
                }

                if (is_array($value)) {
                    $nestedResult = $this->processCacheKey($value);
                    foreach ($nestedResult as $nestedValue) {
                        $result[] = is_string($key) ? $key . "_" . $nestedValue : $nestedValue;
                    }
                } else {
                    $result[] = is_string($key) ? $key . "_" . $value : $value;
                }
            }
        } else {
            if ($element !== null) {
                $result[] = $element;
            }
        }

        // Add page if applicable
        if ($page !== null) {
            $result[] = "page_" . $page;
        }

        return $result;
    }
}
