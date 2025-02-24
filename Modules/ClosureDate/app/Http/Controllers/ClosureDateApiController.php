<?php

namespace Modules\ClosureDate\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Modules\ClosureDate\App\Models\ClosureDate;
use Modules\ClosureDate\Services\ClosureDateApiServiceInterface;
use Modules\Contribution\App\Models\Contribution;

class ClosureDateApiController extends Controller
{
    public function __construct(protected ClosureDateApiServiceInterface $closureDateApiService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($id)
    {
        $contribution= Contribution::findOrFail($id);

        $closureDate = ClosureDate::where('id', $contribution->closure_date_id)
        ->orderBy('final_closure_date', 'desc')
        ->first();

        if ($closureDate && Carbon::now()->greaterThan($closureDate->final_closure_date)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Submissions are closed because deadline has passed.'
            ], 403);
        }else{
            return response()->json(['success' => 'Submission Successful']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
