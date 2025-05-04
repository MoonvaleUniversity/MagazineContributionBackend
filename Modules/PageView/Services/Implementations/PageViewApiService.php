<?php

namespace Modules\PageView\Services\Implementations;

use App\Config\Cache\PageViewCache;
use App\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\PageView\App\Models\MostViewPage;
use Modules\PageView\App\Models\Page;
use Modules\PageView\App\Models\PageView;
use Modules\PageView\Services\PageViewApiServiceInterface;
use Modules\Users\User\App\Models\User;

class PageViewApiService implements PageViewApiServiceInterface
{
    public function get($id = null, $relations = null, $conds = null)
    {
        //read db connection
        $readConnection = config('constants.database.read');
        $params = [$id, $relations, $conds];
        return Cache::remember(PageViewCache::GET_KEY, PageViewCache::GET_EXPIRY, $params, function () use ($id, $relations, $conds, $readConnection) {
            return MostViewPage::on($readConnection)
                ->when($id, function ($q, $id) {
                    $q->where(MostViewPage::id, $id);
                })
                ->when($relations, function ($q, $relations) {
                    $q->with($relations);
                })
                ->when($conds, function ($q, $conds) {
                    $this->searching($q, $conds);
                })
                ->first();
        });
    }

    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null)
    {
        //read db connection
        $readConnection = config('constants.database.read');
        $params = [$relations, $limit, $offset, $noPagination, $pagPerPage, $conds];
        return Cache::remember(PageViewCache::GET_ALL_KEY, PageViewCache::GET_ALL_EXPIRY, $params, function () use ($relations, $limit, $offset, $noPagination, $pagPerPage, $conds, $readConnection) {
            $pageViews = MostViewPage::on($readConnection)
                ->when($relations, function ($q, $relations) {
                    $q->with($relations);
                })
                ->when($limit, function ($q, $limit) {
                    $q->limit($limit);
                })
                ->when($offset, function ($q, $offset) {
                    $q->limit($offset);
                })
                ->when($conds, function ($q, $conds) {
                    $this->searching($q, $conds);
                });

            $pageViews = (!$noPagination || $pagPerPage) ? $pageViews->paginate($pagPerPage) : $pageViews->get();

            return $pageViews;
        });
    }

    public function create($pageViewData)
    {
        // $user = User::find($userId);
        // if (!$user) {
        //     return response()->json(['error' => 'User not found'], 404);
        // }
        DB::beginTransaction();
        try {
            $pageId = $this->createPageView($pageViewData);

            DB::commit();
            Cache::clear([PageViewCache::GET_KEY, PageViewCache::GET_ALL_KEY]);

            return $pageId;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update()
    {

    }

    public function delete()
    {

    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------
    private function createPageView($pageViewData)
    {
        // $pageView = MostViewPage::firstOrCreate(
        //     ['user_id' => $pageViewData, 'page_id' => $pageViewData],
        //     ['view_count' => 1]
        // );

        $pageView = new MostViewPage();
        $pageView -> fill($pageViewData);
        $pageView->save();
        return $pageView;
    }

    private function searching(Builder $query, $conds)
    {
        $query
            ->when(isset($conds['page_id']), function ($q) use ($conds) {
                $q->where(MostViewPage::page_id, $conds['page_id']);
            });

        return $query;
    }
}
