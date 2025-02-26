<?php

namespace Modules\ClosureDate\Services\Implementations;

use Illuminate\Support\Facades\Auth;
use Modules\ClosureDate\App\Models\ClosureDate;
use Modules\ClosureDate\Services\ClosureDateApiServiceInterface;
class ClosureDateApiService implements ClosureDateApiServiceInterface
{
    public function get($id)
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
