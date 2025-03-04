<?php

namespace Modules\Users\Coordinator\Services\Implementations;

use App\Enums\Role;
use Modules\Users\Coordinator\Services\CoordinatorApiServiceInterface;
use Modules\Users\User\Services\UserApiServiceInterface;

class CoordinatorApiService implements CoordinatorApiServiceInterface
{
    public function __construct(protected UserApiServiceInterface $userApiService) {}

    public function get($id = null, $relations = null, $conds = null)
    {
        //read db connection
        $conds['role'] = Role::MARKETING_COORDINATOR->label();
        return $this->userApiService->get($id, $relations, $conds);
    }

    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null)
    {
        //read db connection
        $conds['role'] = Role::MARKETING_COORDINATOR->label();
        return $this->userApiService->getAll($relations, $limit, $offset, $noPagination, $pagPerPage, $conds);
    }

    public function create(array $coordinatorData)
    {
        //write db connection
        $coordinatorData['role'] = Role::MARKETING_COORDINATOR->value;
        return $this->userApiService->create($coordinatorData);
    }

    public function update(int $id, array $coordinatorData)
    {
        //write db connection
        return $this->userApiService->update($id, $coordinatorData);
    }

    public function delete(int $id)
    {
        //write db connection
        return $this->userApiService->delete($id);
    }
}
