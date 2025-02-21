<?php

namespace Modules\ClosureDate\Services\Implementations;

use Modules\ClosureDate\App\Models\ClosureDate;
use Modules\ClosureDate\Services\ClosureDateApiServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ClosureDateApiService implements ClosureDateApiServiceInterface
{
    public function getById($id)
    {
        return ClosureDate::find($id);
    }

    public function getAll()
    {
        return ClosureDate::all();
    }

    public function create($data)
    {
        $data['created_by'] = Auth::id();
        return ClosureDate::create($data);
    }

    public function update($id, $data)
    {
        $closureDate = ClosureDate::findOrFail($id);
        $data['updated_by'] = Auth::id();
        $closureDate->update($data);
        return $closureDate;
    }

    public function delete($id)
    {
        return ClosureDate::destroy($id) > 0;
    }
}
