<?php

namespace Modules\Users\Admin\Services\Implementations;

use App\Enums\Role;
use Modules\Users\Admin\Services\AdminApiServiceInterface;
use Modules\Users\User\Services\UserApiServiceInterface;

class AdminApiService implements AdminApiServiceInterface
{
    public function __construct(protected UserApiServiceInterface $userApiService) {}

    public function get($id = null, $relations = null, $conds = null)
    {
        //read db connection
        $conds['role'] = Role::ADMIN->label();
        return $this->userApiService->get($id, $relations, $conds);
    }

    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null)
    {
        //read db connection
        $conds['role'] = Role::ADMIN->label();
        return $this->userApiService->getAll($relations, $limit, $offset, $noPagination, $pagPerPage, $conds);
    }

    public function create(array $adminData)
    {
        //write db connection
        $adminData['role'] = Role::ADMIN->value;
        return $this->userApiService->create($adminData);
    }

    public function update(int $id, array $adminData)
    {
        //write db connection
        return $this->userApiService->update($id, $adminData);
    }

    public function delete(int $id)
    {
        //write db connection
        return $this->userApiService->delete($id);
    }
}
