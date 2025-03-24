<?php

namespace Modules\AcademicYear\Services\Implementations;

use App\Config\Cache\AcademicYearCache;
use App\Facades\Cache;
use Modules\AcademicYear\App\Models\AcademicYear;
use Modules\AcademicYear\Services\AcademicYearApiServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Modules\ClosureDate\App\Models\ClosureDate;

class AcademicYearApiService implements AcademicYearApiServiceInterface
{
    public function get($id = null, $relations = null, $conds = null)
    {
        //read db connection
        $readConnection = config('constants.database.read');
        $params = [$id, $relations, $conds];
        return Cache::remember(AcademicYearCache::GET_KEY, AcademicYearCache::GET_EXPIRY, $params, function () use ($id, $relations, $conds, $readConnection) {
            return AcademicYear::on($readConnection)
                ->when($id, function ($q, $id) {
                    $q->where(AcademicYear::id, $id);
                })
                ->when($relations, function ($q, $relations) {
                    $q->with($relations);
                })
                ->when($conds, function ($q, $conds) {
                    $this->searching($q, $conds);
                })
                ->first();
        });
    }

    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null)
    {
        //read db connection
        $readConnection = config('constants.database.read');
        $params = [$relations, $limit, $offset, $noPagination, $pagPerPage, $conds];
        return Cache::remember(AcademicYearCache::GET_ALL_KEY, AcademicYearCache::GET_ALL_EXPIRY, $params, function () use ($relations, $limit, $offset, $noPagination, $pagPerPage, $conds, $readConnection) {
            $academicYears = AcademicYear::on($readConnection)
                ->when($relations, function ($q, $relations) {
                    $q->with($relations);
                })
                ->when($limit, function ($q, $limit) {
                    $q->limit($limit);
                })
                ->when($offset, function ($q, $offset) {
                    $q->limit($offset);
                })
                ->when($conds, function ($q, $conds) {
                    $this->searching($q, $conds);
                });

            $academicYears = (!$noPagination || $pagPerPage) ? $academicYears->paginate($pagPerPage) : $academicYears->get();

            return $academicYears;
        });
    }

    public function create($academicYearData)
    {
        //write db connection
        DB::beginTransaction();
        try {
            $academicYear = $this->createAcademicYear($academicYearData);

            DB::commit();
            Cache::clear([AcademicYearCache::GET_KEY, AcademicYearCache::GET_ALL_KEY]);

            return $academicYear;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $academicYearData)
    {
        //write db connection
        DB::beginTransaction();
        try {
            $academicYear = $this->updateAcademicYear($id, $academicYearData);

            DB::commit();
            Cache::clear([AcademicYearCache::GET_KEY, AcademicYearCache::GET_ALL_KEY]);

            return $academicYear;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        //write db connection
        DB::beginTransaction();
        try {
            $name = $this->deleteAcademicYear($id);

            DB::commit();
            Cache::clear([AcademicYearCache::GET_KEY, AcademicYearCache::GET_ALL_KEY]);

            return $name;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------
    private function createAcademicYear($academicYearData)
    {
        $academicYear = new AcademicYear();
        $academicYear->fill($academicYearData);
        $academicYear->save();

        return $academicYear;
    }

    private function updateAcademicYear($id, $academicYearData)
    {
        $academicYear = $this->get($id);
        $academicYear->update($academicYearData);

        return $academicYear;
    }

    private function deleteAcademicYear($id)
    {
        $academicYear = $this->get($id);
        $name = $academicYear->year_name;
        $academicYear->delete();

        return $name;
    }

    private function searching(Builder $query, $conds)
    {
        $query
            ->when(isset($conds['year_name']), function ($q) use ($conds) {
                $q->where(AcademicYear::year_name, $conds['year_name']);
            })
            ->when(isset($conds['closure_date@@id']), function ($q) use ($conds) {
                $q->whereHas('closure_dates', function ($q) use ($conds) {
                    $q->where(ClosureDate::id, $conds['closure_date@@id']);
                });
            });

        return $query;
    }
}
