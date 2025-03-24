<?php

namespace Modules\Users\Manager\Services\Implementations;

use App\Enums\Role;
use Modules\Users\Manager\Services\ManagerApiServiceInterface;
use Modules\Users\User\Services\UserApiServiceInterface;

class ManagerApiService implements ManagerApiServiceInterface
{
    public function __construct(protected UserApiServiceInterface $managerApiService){}

    public function get($id = null, $relations = null, $conds = null)
    {
        //read db connection
        $conds['role'] = Role::MARKETING_MANAGER->label();
        return $this->managerApiService->get($id, $relations, $conds);
    }

    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null)
    {
        //read db connection
        $conds['role'] = Role::MARKETING_MANAGER->label();
        return $this->managerApiService->getAll($relations, $limit, $offset, $noPagination, $pagPerPage, $conds);
    }

    public function create(array $managerData)
    {
        //write db connection
        $managerData['role'] = Role::MARKETING_MANAGER->value;
        return $this->managerApiService->create($managerData);
    }

    public function update(int $id, array $managerData)
    {
        //write db connection
        return $this->managerApiService->update($id, $managerData);
    }

    public function delete(int $id)
    {
        //write db connection
        return $this->managerApiService->delete($id);
    }
}
