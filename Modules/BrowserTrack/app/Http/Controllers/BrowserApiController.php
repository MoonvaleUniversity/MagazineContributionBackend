<?php

namespace Modules\BrowserTrack\App\Http\Controllers;

use App\Events\MostActiveUsersUpdated;
use App\Http\Controllers\Controller;
use App\Models\MostViewPage as ModelsMostViewPage;
use CyrildeWit\EloquentViewable\Support\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\AcademicYear\Services\AcademicYearApiServiceInterface;
use Modules\BrowserTrack\App\Http\Requests\BrowserApiRequest;
use Modules\BrowserTrack\App\Http\Requests\StoreBrowserApiRequest;
use Modules\PageView\App\Http\Requests\StorePageViewApiRequest;
use Modules\PageView\App\Http\Resources\PageViewApiResource;
use Modules\PageView\App\Models\MostViewPage;
use Modules\PageView\App\Models\Page;
use Modules\PageView\Services\PageViewApiServiceInterface;
use Modules\Users\User\App\Models\User;

class BrowserApiController extends Controller
{
    protected $pageViewApiService;

    public function __construct(PageViewApiServiceInterface $pageViewApiService) {
        $this->pageViewApiService = $pageViewApiService;
    }
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $mostActiveUsers = DB::table('most_view_pages as mvp')
    ->join('users', 'mvp.user_id', '=', 'users.id')
    ->select(
        'users.id as user_id',
        'users.name',
        'users.email',
        DB::raw('SUM(mvp.view_count) as total_views')
    )
    ->groupBy('users.id', 'users.name', 'users.email')
    ->orderByDesc('total_views')
    ->get()->all();

    return apiResponse(true, 'Data record successfully', $mostActiveUsers);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrowserApiRequest $request)
    {
        $validatedData = $request->validated();
        $BrowserTrack = $this->pageViewApiService->create($validatedData);
        $data = [
            'browser_track' => new PageViewApiResource($BrowserTrack)
        ];

        return apiResponse(true, 'Data record successfully', $data);
    }



    /**
     * Display the specified resource.
     */
    public function MostVisitedPages()
    {
        $mostVisitedPages = DB::table('most_view_pages as mvp')
        ->select(
            'mvp.page_name',
            DB::raw('SUM(mvp.view_count) as total_views')
        )
        ->groupBy('mvp.page_name')
        ->orderByDesc('total_views')
        ->get();
        return apiResponse(true, 'Data record successfully', $mostVisitedPages);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {

    }

    public function destroy()
    {

    }

}
