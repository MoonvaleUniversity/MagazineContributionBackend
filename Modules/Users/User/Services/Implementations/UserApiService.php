<?php

namespace Modules\Users\User\Services\Implementations;

use App\Config\Cache\UserCache;
use App\Enums\Role;
use App\Facades\Cache;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Modules\Users\User\App\Models\User;
use Modules\Users\User\Services\UserApiServiceInterface;

class UserApiService implements UserApiServiceInterface
{
    public function get($id = null, $relations = null, array $conds = null)
    {
        //read db connection
        $readConnection = config('constants.database.read');
        $param = [$id, $relations, $conds];
        return Cache::remember(UserCache::GET_KEY, UserCache::GET_EXPIRY, $param, function () use ($id, $relations, $conds, $readConnection) {
            return User::on($readConnection)
                ->when($id, function ($q, $id) {
                    $q->where(User::id, $id);
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
        $param = [$relations, $limit, $offset, $noPagination, $pagPerPage, $conds];
        return Cache::remember(UserCache::GET_ALL_KEY, UserCache::GET_ALL_EXPIRY, $param, function () use ($relations, $limit, $offset, $noPagination, $pagPerPage, $readConnection, $conds) {
            $users = User::on($readConnection)
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

            $users = (!$noPagination || $pagPerPage) ? $users->paginate($pagPerPage) : $users->get();

            return $users;
        });
    }

    public function create($userData, $role)
    {
        //write db connection
        DB::beginTransaction();
        try {
            $user = $this->createUser($userData);
            $this->assignRole($user,$role);

            DB::commit();
            Cache::clear(UserCache::GET_ALL_KEY);

            return $user;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update()
    {
        //write db connection
    }

    public function delete()
    {
        //write db connection
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------
    private function createUser($userData)
    {
        $user = new User();
        $user->fill($userData);
        $user->save();

        return $user;
    }

    private function searching(Builder $query, $conds)
    {
        $query
            ->when(isset($conds['role']), function ($q) use ($conds) {
                $q->whereHas('roles', function ($query) use ($conds) {
                    $query->where('name', $conds['role']);
                });
            })
            ->when(isset($conds['email']), function ($q) use ($conds) {
                $q->where(User::email, $conds['email']);
            });

        return $query;
    }

    //-------------------------------------------------------------------
    // Others
    //-------------------------------------------------------------------
    private function assignRole(User $user, $role)
    {
        $role = Role::tryFrom($role);

        if ($role) {
            $user->assignRole($role->label());
        } else {
            throw new Exception('Invalid Role');
        }
    }
}
