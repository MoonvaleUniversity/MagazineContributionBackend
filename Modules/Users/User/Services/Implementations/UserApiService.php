<?php

namespace Modules\Users\User\Services\Implementations;

use Modules\Users\User\App\Models\User;
use Modules\Users\User\Services\UserApiServiceInterface;

class UserApiService implements UserApiServiceInterface
{
    public function get($id = null, $relations = null)
    {
        //read db connection
        $readConnection = config('constants.database.read');
        $user = User::on($readConnection)
            ->when($id, function ($q, $id) {
                $q->where(User::id, $id);
            })
            ->when($relations, function ($q, $relations) {
                $q->with($relations);
            })
            ->first();
        return $user;
    }

    public function getAll()
    {
        //read db connection
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
