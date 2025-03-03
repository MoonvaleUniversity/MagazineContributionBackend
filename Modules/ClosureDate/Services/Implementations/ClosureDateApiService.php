<?php

namespace Modules\ClosureDate\Services\Implementations;

use App\Config\Cache\ClosureDateCache;
use App\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\ClosureDate\App\Models\ClosureDate;
use Modules\ClosureDate\Services\ClosureDateApiServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Modules\AcademicYear\App\Models\AcademicYear;

class ClosureDateApiService implements ClosureDateApiServiceInterface
{
    public function get($id = null, $relations = null, $conds = null)
    {
        //read db connection
        $readConnection = config('constants.database.read');
        $params = [$id, $relations, $conds];
        return Cache::remember(ClosureDateCache::GET_KEY, ClosureDateCache::GET_EXPIRY, $params, function () use ($id, $relations, $conds, $readConnection) {
            return ClosureDate::on($readConnection)
                ->when($id, function ($q, $id) {
                    $q->where(ClosureDate::id, $id);
                })
                ->when($relations, function ($q, $relations) {
                    $q->with($relations);
                })
                ->when($conds, function ($q, $conds) {
                    $this->searching($q, $conds);
                })
                ->first();
        });
        // return ClosureDate::find($id);
    }

    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null)
    {
        //read db connection
        $readConnection = config('constants.database.read');
        $params = [$relations, $limit, $offset, $noPagination, $pagPerPage, $conds];
        return Cache::remember(ClosureDateCache::GET_ALL_KEY, ClosureDateCache::GET_ALL_EXPIRY, $params, function () use ($relations, $limit, $offset, $noPagination, $pagPerPage, $conds, $readConnection) {
            $closureDates = ClosureDate::on($readConnection)
                ->when($relations, function ($q, $relations) {
                    $q->with($relations);
                })
                ->when($limit, function ($q, $limit) {
                    $q->limit($limit);
                })
                ->when($offset, function ($q, $offset) {
                    $q->offset($offset);
                });

            $closureDates = (!$noPagination || $pagPerPage) ? $closureDates->paginate($pagPerPage) : $closureDates->get();

            return $closureDates;
        });
    }

    public function create($closureDateData)
    {
        DB::beginTransaction();
        try {
            $closureDate = $this->createClosureDate($closureDateData);

            DB::commit();
            Cache::clear([ClosureDateCache::GET_KEY, ClosureDateCache::GET_ALL_KEY]);

            return $closureDate;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $closureDateData)
    {
        //write db connection
        DB::beginTransaction();
        try {
            $closureDate = $this->get($id);
            $closureDate->fill($closureDateData);
            $closureDate->save();

            DB::commit();
            Cache::clear([ClosureDateCache::GET_ALL_KEY, ClosureDateCache::GET_KEY]);

            return $closureDate;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        $name = $this->deleteClosureDate($id);
        Cache::clear([ClosureDateCache::GET_ALL_KEY, ClosureDateCache::GET_KEY]);
        return $name;
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------
    private function createClosureDate($closureDateData)
    {
        $closureDate = new ClosureDate();
        $closureDate->fill($closureDateData);
        $closureDate->save();

        return $closureDate;
    }

    private function deleteClosureDate($id)
    {
        $closureDate = $this->get($id);
        $name = $closureDate->closure_date;
        $closureDate->delete();

        return $name;
    }

    private function searching(Builder $query, $conds)
    {
        $query
            ->when(isset($conds['academic_year_id']), function ($q) use ($conds) {
                $q->where(ClosureDate::academic_year_id, $conds['academic_year_id']);
            })
            ->when(isset($conds['academic_year_id@@year_name']), function ($q) use ($conds) {
                $q->whereHas('academic_year', function ($q) use ($conds) {
                    $q->where(AcademicYear::year_name, $conds['academic_year_id@@year_name']);
                });
            });

        return $query;
    }
}
