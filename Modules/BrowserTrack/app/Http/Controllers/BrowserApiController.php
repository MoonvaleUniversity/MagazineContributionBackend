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
use Modules\BrowserTrack\App\Http\Resources\BrowserApiResource;
use Modules\BrowserTrack\Services\BrowserApiServiceInterface;
use Modules\PageView\App\Http\Requests\StorePageViewApiRequest;
use Modules\PageView\App\Http\Resources\PageViewApiResource;
use Modules\PageView\App\Models\MostViewPage;
use Modules\PageView\App\Models\Page;
use Modules\PageView\Services\PageViewApiServiceInterface;
use Modules\Users\User\App\Models\User;

class BrowserApiController extends Controller
{
    protected $browserApiService;

    public function __construct(BrowserApiServiceInterface $browserApiService) {
        $this->browserApiService = $browserApiService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $mostBrowserUse = DB::table('browser_tracks')
            ->select(
                'browser_name',
                DB::raw('count(*) as total_visits')
            )
            ->groupBy('browser_name')
            ->orderByDesc('total_visits')
            ->get();

        return apiResponse(true, 'Browser usage stats fetched successfully', $mostBrowserUse);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrowserApiRequest $request)
    {
        $validatedData = $request->validated();
        $BrowserTrack = $this->browserApiService->create($validatedData);
        $data = [
            'browser_track' => new BrowserApiResource($BrowserTrack)
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
