<?php

namespace Modules\Users\User\Services\Implementations;

use App\Config\Cache\UserCache;
use App\Facades\Cache;
use Modules\Users\User\App\Models\User;
use Modules\Users\User\Services\UserApiServiceInterface;

class UserApiService implements UserApiServiceInterface
{
    public function get($id = null, $relations = null)
    {
        //read db connection
        $readConnection = config('constants.database.read');
        $param = [$id, $relations];
        return Cache::remember(UserCache::GET_KEY, UserCache::GET_EXPIRY, $param, function () use ($id, $relations, $readConnection) {
            return User::on($readConnection)
                ->when($id, function ($q, $id) {
                    $q->where(User::id, $id);
                })
                ->when($relations, function ($q, $relations) {
                    $q->with($relations);
                })
                ->first();
        });
    }

    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $role = null)
    {
        //read db connection
        $readConnection = config('constants.database.read');
        $param = [$relations, $limit, $offset, $noPagination, $pagPerPage, $role];
        return Cache::remember(UserCache::GET_ALL_KEY, UserCache::GET_ALL_EXPIRY, $param, function () use ($relations, $limit, $offset, $noPagination, $pagPerPage, $readConnection, $role) {
            $users = User::on($readConnection)
                ->when($role, function ($q, $role) {
                    $q->whereHas('roles', function ($query) use ($role) {
                        $query->where('name', $role);
                    });
                })
                ->when($relations, function ($q, $relations) {
                    $q->with($relations);
                })
                ->when($limit, function ($q, $limit) {
                    $q->limit($limit);
                })
                ->when($offset, function ($q, $offset) {
                    $q->offset($offset);
                });

            $users = (!$noPagination || $pagPerPage) ? $users->paginate($pagPerPage) : $users->get();

            return $users;
        });
    }

    public function create()
    {
        //write db connection
    }

    public function update()
    {
        //write db connection
    }

    public function delete()
    {
        //write db connection
    }
}
