<?php

namespace Modules\Users\Guest\Services\Implementations;

use App\Enums\Role;
use Modules\Users\Guest\Services\GuestApiServiceInterface;
use Modules\Users\User\Services\UserApiServiceInterface;

class GuestApiService implements GuestApiServiceInterface
{
    public function __construct(protected UserApiServiceInterface $guestApiService) {}

    public function get($id = null, $relations = null, $conds = null)
    {
        //read db connection
        $conds['role'] = Role::GUEST->label();
        return $this->guestApiService->get($id, $relations, $conds);
    }

    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null)
    {
        //read db connection
        $conds['role'] = Role::GUEST->label();
        return $this->guestApiService->getAll($relations, $limit, $offset, $noPagination, $pagPerPage, $conds);
    }

    public function create(array $guestData)
    {
        //write db connection
        $guestData['role'] = Role::GUEST->value;
        $guestData['is_approved'] = 0;
        return $this->guestApiService->create($guestData);
    }

    public function update(int $id, array $guestData)
    {
        //write db connection
        return $this->guestApiService->update($id, $guestData);
    }

    public function delete(int $id)
    {
        //write db connection
        return $this->guestApiService->delete($id);
    }
}
