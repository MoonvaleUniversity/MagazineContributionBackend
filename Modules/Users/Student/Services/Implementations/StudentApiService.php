<?php

namespace Modules\Users\Student\Services\Implementations;

use App\Enums\Role;
use Modules\Users\Student\Services\StudentApiServiceInterface;

class StudentApiService implements StudentApiServiceInterface
{
    public function __construct(protected StudentApiServiceInterface $studentApiService) {}

    public function get($id = null, $relations = null, $conds = null)
    {
        //read db connection
        $conds['role'] = Role::STUDENT->label();
        return $this->studentApiService->get($id, $relations, $conds);
    }

    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null)
    {
        //read db connection
        $conds['role'] = Role::STUDENT->label();
        return $this->studentApiService->getAll($relations, $limit, $offset, $noPagination, $pagPerPage, $conds);
    }

    public function create(array $studentData)
    {
        //write db connection
        $studentData['role'] = Role::STUDENT->value;
        return $this->studentApiService->create($studentData);
    }

    public function update(int $id, array $studentData)
    {
        //write db connection
        return $this->studentApiService->update($id, $studentData);
    }

    public function delete(int $id)
    {
        //write db connection
        return $this->studentApiService->delete($id);
    }
}
