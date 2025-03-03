<?php

namespace Modules\Faculty\Services\Implementations;

use App\Config\Cache\FacultyCache;
use App\Facades\Cache;
use App\Models\User;
use Modules\Faculty\App\Models\Faculty;
use Modules\Faculty\Services\FacultyApiServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class FacultyApiService implements FacultyApiServiceInterface
{
    public function get($id = null, $relations = null, $conds = null)
    {
        //read db connection
        $readConnection = config('constants.database.read');
        $params = [$id, $relations, $conds];
        return Cache::remember(FacultyCache::GET_KEY, FacultyCache::GET_EXPIRY, $params, function () use ($id, $relations, $conds, $readConnection) {
            return Faculty::on($readConnection)
                ->when($id, function ($q, $id) {
                    $q->where(Faculty::id, $id);
                })
                ->when($relations, function ($q, $relations) {
                    $q->with($relations);
                })
                ->when($conds, function ($q) use ($conds) {
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
        return Cache::remember(FacultyCache::GET_KEY, FacultyCache::GET_EXPIRY, $params, function () use ($relations, $limit, $offset, $noPagination, $pagPerPage, $conds, $readConnection) {
            $faculties = Faculty::on($readConnection)
                ->when($relations, function ($q, $relations) {
                    $q->with($relations);
                })
                ->when($limit, function ($q, $limit) {
                    $q->limit($limit);
                })
                ->when($offset, function ($q, $offset) {
                    $q->offset($offset);
                })
                ->when($conds, function ($q, $conds) {
                    $this->searching($q, $conds);
                });

            $faculties = (!$noPagination || $pagPerPage) ? $faculties->paginate($pagPerPage) : $faculties->get();

            return $faculties;
        });
    }

    public function create($facultyData)
    {
        //write db connection
        DB::beginTransaction();
        try {
            $faculty = $this->createFaculty($facultyData);

            DB::commit();
            Cache::clear([FacultyCache::GET_KEY, FacultyCache::GET_ALL_KEY]);

            return $faculty;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $facultyData)
    {
        //write db connection
        DB::beginTransaction();
        try {
            $faculty = $this->updateFaculty($id, $facultyData);

            DB::commit();
            Cache::clear([FacultyCache::GET_KEY, FacultyCache::GET_ALL_KEY]);

            return $faculty;
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
            $name = $this->deleteFaculty($id);

            DB::commit();
            Cache::clear([FacultyCache::GET_KEY, FacultyCache::GET_ALL_KEY]);

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
    private function createFaculty($facultyData)
    {
        $faculty = new Faculty();
        $faculty->fill($facultyData);
        $faculty->save();

        return $faculty;
    }

    private function updateFaculty($id, $facultyData)
    {
        $faculty = $this->get($id);
        $faculty->update($facultyData);
        $faculty->save();

        return $faculty;
    }

    private function deleteFaculty($id)
    {
        $faculty = $this->get($id);
        $name = $faculty->name;
        $faculty->delete();

        return $name;
    }

    private function searching(Builder $query, $conds)
    {
        $query
            ->when(isset($conds['name']), function ($q) use ($conds) {
                $q->where(Faculty::name, $conds['name']);
            })
            ->when(isset($conds['student@@id']), function ($q) use ($conds) {
                $q->whereHas('students', function ($q) use ($conds) {
                    $q->where(User::id, $conds['student@@id']);
                });
            })
            ->when(isset($conds['user@@id']), function ($q) use ($conds) {
                $q->whereHas('users', function ($q) use ($conds) {
                    $q->where(User::id, $conds['user@@id']);
                });
            });
        return $query;
    }
}
