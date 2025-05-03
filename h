[33mcommit 60f6ef31bfe5662d3856443874d5bd725f4e6156[m[33m ([m[1;36mHEAD[m[33m -> [m[1;32mtracking[m[33m)[m
Author: moonvale <moonvaleuniversity@gmail.com>
Date:   Sat Apr 26 13:29:33 2025 +0630

    26 April 2025 Tracking

[1mdiff --git a/Modules/BrowserTrack/Services/BrowserApiServiceInterface.php b/Modules/BrowserTrack/Services/BrowserApiServiceInterface.php[m
[1mnew file mode 100644[m
[1mindex 0000000..dc86b36[m
[1m--- /dev/null[m
[1m+++ b/Modules/BrowserTrack/Services/BrowserApiServiceInterface.php[m
[36m@@ -0,0 +1,18 @@[m
[32m+[m[32m<?php[m
[32m+[m
[32m+[m[32mnamespace Modules\BrowserTrack\Services;[m
[32m+[m
[32m+[m[32muse Illuminate\Http\Request;[m
[32m+[m[32muse Modules\PageView\App\Models\Page;[m
[32m+[m
[32m+[m[32minterface BrowserApiServiceInterface {[m
[32m+[m[32m    public function get($id = null, $relations = null, $conds = null);[m
[32m+[m
[32m+[m[32m    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null);[m
[32m+[m
[32m+[m[32m    public function create($pageViewData);[m
[32m+[m
[32m+[m[32m    public function update();[m
[32m+[m
[32m+[m[32m    public function delete();[m
[32m+[m[32m}[m
[1mdiff --git a/Modules/BrowserTrack/Services/Implementations/BrowserApiService.php b/Modules/BrowserTrack/Services/Implementations/BrowserApiService.php[m
[1mnew file mode 100644[m
[1mindex 0000000..acdd8db[m
[1m--- /dev/null[m
[1m+++ b/Modules/BrowserTrack/Services/Implementations/BrowserApiService.php[m
[36m@@ -0,0 +1,122 @@[m
[32m+[m[32m<?php[m
[32m+[m
[32m+[m[32mnamespace Modules\BrowserTrack\Services\Implementations;[m
[32m+[m
[32m+[m[32muse App\Config\Cache\BrowserCache;[m
[32m+[m[32muse App\Facades\Cache;[m
[32m+[m[32muse Carbon\Carbon;[m
[32m+[m[32muse Illuminate\Database\Eloquent\Builder;[m
[32m+[m[32muse Illuminate\Http\Request;[m
[32m+[m[32muse Illuminate\Support\Facades\Auth;[m
[32m+[m[32muse Illuminate\Support\Facades\DB;[m
[32m+[m[32muse Modules\BrowserTrack\Services\BrowserApiServiceInterface;[m
[32m+[m[32muse Modules\BrowserTrack\App\Models\BrowserTrack;[m
[32m+[m
[32m+[m[32mclass BrowserApiService implements BrowserApiServiceInterface[m
[32m+[m[32m{[m
[32m+[m[32m    public function get($id = null, $relations = null, $conds = null)[m
[32m+[m[32m    {[m
[32m+[m[32m        //read db connection[m
[32m+[m[32m        $readConnection = config('constants.database.read');[m
[32m+[m[32m        $params = [$id, $relations, $conds];[m
[32m+[m[32m        return Cache::remember(BrowserCache::GET_KEY, BrowserCache::GET_EXPIRY, $params, function () use ($id, $relations, $conds, $readConnection) {[m
[32m+[m[32m            return BrowserTrack::on($readConnection)[m
[32m+[m[32m                ->when($id, function ($q, $id) {[m
[32m+[m[32m                    $q->where(BrowserTrack::id, $id);[m
[32m+[m[32m                })[m
[32m+[m[32m                ->when($relations, function ($q, $relations) {[m
[32m+[m[32m                    $q->with($relations);[m
[32m+[m[32m                })[m
[32m+[m[32m                ->when($conds, function ($q, $conds) {[m
[32m+[m[32m                    $this->searching($q, $conds);[m
[32m+[m[32m                })[m
[32m+[m[32m                ->first();[m
[32m+[m[32m        });[m
[32m+[m[32m    }[m
[32m+[m
[32m+[m[32m    public function getAll($relations = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null)[m
[32m+[m[32m    {[m
[32m+[m[32m        //read db connection[m
[32m+[m[32m        $readConnection = config('constants.database.read');[m
[32m+[m[32m        $params = [$relations, $limit, $offset, $noPagination, $pagPerPage, $conds];[m
[32m+[m[32m        return Cache::remember(BrowserCache::GET_ALL_KEY, BrowserCache::GET_ALL_EXPIRY, $params, function () use ($relations, $limit, $offset, $noPagination, $pagPerPage, $conds, $readConnection) {[m
[32m+[m[32m            $pageViews = BrowserTrack::on($readConnection)[m
[32m+[m[32m                ->when($relations, function ($q, $relations) {[m
[32m+[m[32m                    $q->with($relations);[m
[32m+[m[32m                })[m
[32m+[m[32m                ->when($limit, function ($q, $limit) {[m
[32m+[m[32m                    $q->limit($limit);[m
[32m+[m[32m                })[m
[32m+[m[32m                ->when($offset, function ($q, $offset) {[m
[32m+[m[32m                    $q->limit($offset);[m
[32m+[m[32m                })[m
[32m+[m[32m                ->when($conds, function ($q, $conds) {[m
[32m+[m[32m                    $this->searching($q, $conds);[m
[32m+[m[32m                });[m
[32m+[m
[32m+[m[32m            $pageViews = (!$noPagination || $pagPerPage) ? $pageViews->paginate($pagPerPage) : $pageViews->get();[m
[32m+[m
[32m+[m[32m            return $pageViews;[m
[32m+[m[32m        });[m
[32m+[m[32m    }[m
[32m+[m
[32m+[m[32m    public function create($pageViewData)[m
[32m+[m[32m    {[m
[32m+[m[32m        // $user = User::find($userId);[m
[32m+[m[32m        // if (!$user) {[m
[32m+[m[32m        //     return response()->json(['error' => 'User not found'], 404);[m
[32m+[m[32m        // }[m
[32m+[m[32m        DB::beginTransaction();[m
[32m+[m[32m        try {[m
[32m+[m[32m            $pageId = $this->createPageView($pageViewData);[m
[32m+[m
[32m+[m[32m            DB::commit();[m
[32m+[m[32m            Cache::clear([BrowserCache::GET_KEY, BrowserCache::GET_ALL_KEY]);[m
[32m+[m
[32m+[m[32m            return $pageId;[m
[32m+[m[32m        } catch (\Throwable $e) {[m
[32m+[m[32m            DB::rollBack();[m
[32m+[m[32m            throw $e;[m
[32m+[m[32m        }[m
[32m+[m[32m    }[m
[32m+[m
[32m+[m[32m    public function update()[m
[32m+[m[32m    {[m
[32m+[m
[32m+[m[32m    }[m
[32m+[m
[32m+[m[32m    public function delete()[m
[32m+[m[32m    {[m
[32m+[m
[32m+[m[32m    }[m
[32m+[m
[32m+[m[32m    ////////////////////////////////////////////////////////////////////[m
[32m+[m[32m    /// Private Functions[m
[32m+[m[32m    ////////////////////////////////////////////////////////////////////[m
[32m+[m
[32m+[m[32m    //-------------------------------------------------------------------[m
[32m+[m[32m    // Database[m
[32m+[m[32m    //-------------------------------------------------------------------[m
[32m+[m[32m    private function createPageView($pageViewData)[m
[32m+[m[32m    {[m
[32m+[m[32m        // $pageView = Browser::firstOrCreate([m
[32m+[m[32m        //     ['user_id' => $pageViewData, 'id' => $pageViewData],[m
[32m+[m[32m        //     ['view_count' => 1][m
[32m+[m[32m        // );[m
[32m+[m
[32m+[m[32m        $pageView = new BrowserTrack();[m
[32m+[m[32m        $pageView -> fill($pageViewData);[m
[32m+[m[32m        $pageView->save();[m
[32m+[m[32m        return $pageView;[m
[32m+[m[32m    }[m
[32m+[m
[32m+[m[32m    private function searching(Builder $query, $conds)[m
[32m+[m[32m    {[m
[32m+[m[32m        $query[m
[32m+[m[32m            ->when(isset($conds['id']), function ($q) use ($conds) {[m
[32m+[m[32m                $q->where(BrowserTrack::id, $conds['id']);[m
[32m+[m[32m            });[m
[32m+[m
[32m+[m[32m        return $query;[m
[32m+[m[32m    }[m
[32m+[m[32m}[m
[1mdiff --git a/Modules/BrowserTrack/app/Http/Controllers/BrowserApiController.php b/Modules/BrowserTrack/app/Http/Controllers/BrowserApiController.php[m
[1mnew file mode 100644[m
[1mindex 0000000..332e42f[m
[1m--- /dev/null[m
[1m+++ b/Modules/BrowserTrack/app/Http/Controllers/BrowserApiController.php[m
[36m@@ -0,0 +1,94 @@[m
[32m+[m[32m<?php[m
[32m+[m
[32m+[m[32mnamespace Modules\BrowserTrack\App\Http\Controllers;[m
[32m+[m
[32m+[m[32muse App\Events\MostActiveUsersUpdated;[m
[32m+[m[32muse App\Http\Controllers\Controller;[m
[32m+[m[32muse App\Models\MostViewPage as ModelsMostViewPage;[m
[32m+[m[32muse CyrildeWit\EloquentViewable\Support\Period;[m
[32m+[m[32muse Illuminate\Http\Request;[m
[32m+[m[32muse Illuminate\Support\Facades\Auth;[m
[32m+[m[32muse Illuminate\Support\Facades\DB;[m
[32m+[m[32muse Modules\AcademicYear\Services\AcademicYearApiServiceInterface;[m
[32m+[m[32muse Modules\BrowserTrack\App\Http\Requests\BrowserApiRequest;[m
[32m+[m[32muse Modules\BrowserTrack\App\Http\Requests\StoreBrowserApiRequest;[m
[32m+[m[32muse Modules\PageView\App\Http\Requests\StorePageViewApiRequest;[m
[32m+[m[32muse Modules\PageView\App\Http\Resources\PageViewApiResource;[m
[32m+[m[32muse Modules\PageView\App\Models\MostViewPage;[m
[32m+[m[32muse Modules\PageView\App\Models\Page;[m
[32m+[m[32muse Modules\PageView\Services\PageViewApiServiceInterface;[m
[32m+[m[32muse Modules\Users\User\App\Models\User;[m
[32m+[m
[32m+[m[32mclass BrowserApiController extends Controller[m
[32m+[m[32m{[m
[32m+[m[32m    protected $pageViewApiService;[m
[32m+[m
[32m+[m[32m    public function __construct(PageViewApiServiceInterface $pageViewApiService) {[m
[32m+[m[32m        $this->pageViewApiService = $pageViewApiService;[m
[32m+[m[32m    }[m
[32m+[m[32m    /**[m
[32m+[m[32m     * Display a listing of the resource.[m
[32m+[m[32m     */[m
[32m+[m[32mpublic function index(Request $request)[m
[32m+[m[32m{[m
[32m+[m[32m    $mostActiveUsers = DB::table('most_view_pages as mvp')[m
[32m+[m[32m    ->join('users', 'mvp.user_id', '=', 'users.id')[m
[32m+[m[32m    ->select([m
[32m+[m[32m        'users.id as user_id',[m
[32m+[m[32m        'users.name',[m
[32m+[m[32m        'users.email',[m
[32m+[m[32m        DB::raw('SUM(mvp.view_count) as total_views')[m
[32m+[m[32m    )[m
[32m+[m[32m    ->groupBy('users.id', 'users.name', 'users.email')[m
[32m+[m[32m    ->orderByDesc('total_views')[m
[32m+[m[32m    ->get()->all();[m
[32m+[m
[32m+[m[32m    return apiResponse(true, 'Data record successfully', $mostActiveUsers);[m
[32m+[m[32m}[m
[32m+[m
[32m+[m[32m    /**[m
[32m+[m[32m     * Store a newly created resource in storage.[m
[32m+[m[32m     */[m
[32m+[m[32m    public function store(StoreBrowserApiRequest $request)[m
[32m+[m[32m    {[m
[32m+[m[32m        $validatedData = $request->validated();[m
[32m+[m[32m        $BrowserTrack = $this->pageViewApiService->create($validatedData);[m
[32m+[m[32m        $data = [[m
[32m+[m[32m            'browser_track' => new PageViewApiResource($BrowserTrack)[m
[32m+[m[32m        ];[m
[32m+[m
[32m+[m[32m        return apiResponse(true, 'Data record successfully', $data);[m
[32m+[m[32m    }[m
[32m+[m
[32m+[m
[32m+[m
[32m+[m[32m    /**[m
[32m+[m[32m     * Display the specified resource.[m
[32m+[m[32m     */[m
[32m+[m[32m    public function MostVisitedPages()[m
[32m+[m[32m    {[m
[32m+[m[32m        $mostVisitedPages = DB::table('most_view_pages as mvp')[m
[32m+[m[32m        ->select([m
[32m+[m[32m            'mvp.page_name',[m
[32m+[m[32m            DB::raw('SUM(mvp.view_count) as total_views')[m
[32m+[m[32m        )[m
[32m+[m[32m        ->groupBy('mvp.page_name')[m
[32m+[m[32m        ->orderByDesc('total_views')[m
[32m+[m[32m        ->get();[m
[32m+[m[32m        return apiResponse(true, 'Data record successfully', $mostVisitedPages);[m
[32m+[m[32m    }[m
[32m+[m
[32m+[m[32m    /**[m
[32m+[m[32m     * Update the specified resource in storage.[m
[32m+[m[32m     */[m
[32m+[m[32m    public function update()[m
[32m+[m[32m    {[m
[32m+[m
[32m+[m[32m    }[m
[32m+[m
[32m+[m[32m    public function destroy()[m
[32m+[m[32m    {[m
[32m+[m
[32m+[m[32m    }[m
[32m+[m
[32m+[m[32m}[m
[1mdiff --git a/Modules/BrowserTrack/app/Http/Requests/StoreBrowserApiRequest.php b/Modules/BrowserTrack/app/Http/Requests/StoreBrowserApiRequest.php[m
[1mnew file mode 100644[m
[1mindex 0000000..8045402[m
[1m--- /dev/null[m
[1m+++ b/Modules/BrowserTrack/app/Http/Requests/StoreBrowserApiRequest.php[m
[36m@@ -0,0 +1,39 @@[m
[32m+[m[32m<?php[m
[32m+[m
[32m+[m[32mnamespace Modules\BrowserTrack\App\Http\Requests;[m
[32m+[m
[32m+[m[32muse Illuminate\Foundation\Http\FormRequest;[m
[32m+[m[32muse Illuminate\Support\Facades\Auth;[m
[32m+[m[32muse Modules\Users\Admin\Services\AdminApiServiceInterface;[m
[32m+[m
[32m+[m[32mclass StoreBrowserApiRequest extends FormRequest[m
[32m+[m[32m{[m
[32m+[m[32m    public function __construct(protected AdminApiServiceInterface $adminApiService) {}[m
[32m+[m[32m    /**[m
[32m+[m[32m     * Determine if the user is authorized to make this request.[m
[32m+[m[32m     */[m
[32m+[m[32m    public function authorize(): bool[m
[32m+[m[32m    {[m
[32m+[m[32m        //     $userId = Auth::user()->id;[m
[32m+[m[32m        //     $user = $this->adminApiService->get($userId);[m
[32m+[m[32m        //     if ($user) {[m
[32m+[m[32m        //         return true;[m
[32m+[m[32m        //     }[m
[32m+[m[32m        // return false;[m
[32m+[m[32m        return true;[m
[32m+[m[32m    }[m
[32m+[m
[32m+[m[32m    /**[m
[32m+[m[32m     * Get the validation rules that apply to the request.[m
[32m+[m[32m     *[m
[32m+[m[32m     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>[m
[32m+[m[32m     */[m
[32m+[m[32m    public function rules(): array[m
[32m+[m[32m    {[m
[32m+[m[32m        return [[m
[32m+[m[32m            'browser_name' => 'required|string',[m
[32m+[m[32m                'browser_version' => 'required|string',[m
[32m+[m[32m                'os' => 'required|string',[m
[32m+[m[32m        ];[m
[32m+[m[32m    }[m
[32m+[m[32m}[m
[1mdiff --git a/Modules/BrowserTrack/app/Http/Resources/BrowserApiResource.php b/Modules/BrowserTrack/app/Http/Resources/BrowserApiResource.php[m
[1mnew file mode 100644[m
[1mindex 0000000..9ba1a8a[m
[1m--- /dev/null[m
[1m+++ b/Modules/BrowserTrack/app/Http/Resources/BrowserApiResource.php[m
[36m@@ -0,0 +1,19 @@[m
[32m+[m[32m<?php[m
[32m+[m
[32m+[m[32mnamespace Modules\BrowserTrack\App\Http\Resources;[m
[32m+[m
[32m+[m[32muse Illuminate\Http\Request;[m
[32m+[m[32muse Illuminate\Http\Resources\Json\JsonResource;[m
[32m+[m
[32m+[m[32mclass BrowserApiResource extends JsonResource[m
[32m+[m[32m{[m
[32m+[m[32m    /**[m
[32m+[m[32m     * Transform the resource into an array.[m
[32m+[m[32m     *[m
[32m+[m[32m     * @return array<string, mixed>[m
[32m+[m[32m     */[m
[32m+[m[32m    public function toArray(Request $request): array[m
[32m+[m[32m    {[m
[32m+[m[32m        return parent::toArray($request);[m
[32m+[m[32m    }[m
[32m+[m[32m}[m
[1mdiff --git a/Modules/BrowserTrack/app/Models/BrowserTrack.php b/Modules/BrowserTrack/app/Models/BrowserTrack.php[m
[1mnew file mode 100644[m
[1mindex 0000000..5e28281[m
[1m--- /dev/null[m
[1m+++ b/Modules/BrowserTrack/app/Models/BrowserTrack.php[m
[36m@@ -0,0 +1,23 @@[m
[32m+[m[32m<?php[m
[32m+[m
[32m+[m[32mnamespace Modules\BrowserTrack\App\Models;[m
[32m+[m
[32m+[m[32muse Illuminate\Database\Eloquent\Model;[m
[32m+[m[32muse Modules\Users\User\App\Models\User;[m
[32m+[m
[32m+[m[32mclass BrowserTrack extends Model[m
[32m+[m[32m{[m
[32m+[m[32m    protected $fillable = ['user_id', 'browser_name', 'browser_version', 'os'];[m
[32m+[m[32m    const id = 'id';[m
[32m+[m[32m    const user_id = 'user_id';[m
[32m+[m[32m    const browser_name = 'browser_name';[m
[32m+[m[32m    const browser_version = 'browser_version';[m
[32m+[m[32m    const os = 'os';[m
[32m+[m
[32m+[m[32m    public function user()[m
[32m+[m[32m    {[m
[32m+[m[32m        return $this->belongsTo(User::class, 'user_id','id');[m
[32m+[m[32m    }[m
[32m+[m
[32m+[m
[32m+[m[32m}[m
[1mdiff --git a/Modules/BrowserTrack/app/Providers/BrowserApiServiceProvider.php b/Modules/BrowserTrack/app/Providers/BrowserApiServiceProvider.php[m
[1mnew file mode 100644[m
[1mindex 0000000..59a8174[m
[1m--- /dev/null[m
[1m+++ b/Modules/BrowserTrack/app/Providers/BrowserApiServiceProvider.php[m
[36m@@ -0,0 +1,33 @@[m
[32m+[m[32m<?php[m
[32m+[m
[32m+[m[32mnamespace Modules\BrowserTrack\App\Providers;[m
[32m+[m
[32m+[m[32muse Illuminate\Support\Facades\Route;[m
[32m+[m[32muse Illuminate\Support\ServiceProvider;[m
[32m+[m[32muse Modules\BrowserTrack\Services\BrowserApiServiceInterface;[m
[32m+[m[32muse Modules\BrowserTrack\Services\Implementations\BrowserApiService;[m
[32m+[m
[32m+[m
[32m+[m[32mclass BrowserApiServiceProvider extends ServiceProvider[m
[32m+[m[32m{[m
[32m+[m[32m    /**[m
[32m+[m[32m     * Register services.[m
[32m+[m[32m     */[m
[32m+[m[32m    public function register(): void[m
[32m+[m[32m    {[m
[32m+[m[32m        $this->app->bind(BrowserApiServiceInterface::class, BrowserApiService::class);[m
[32m+[m[32m    }[m
[32m+[m
[32m+[m[32m    /**[m
[32m+[m[32m     * Bootstrap services.[m
[32m+[m[32m     */[m
[32m+[m[32m    public function boot(): void[m
[32m+[m[32m    {[m
[32m+[m[32m        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');[m
[32m+[m[32m        Route::prefix('api/v1')[m
[32m+[m[32m            ->middleware('api') // Apply any middleware if needed[m
[32m+[m[32m            ->group(function () {[m
[32m+[m[32m                require __DIR__ . '/../../routes/api_v1.0.php';[m
[32m+[m[32m            });[m
[32m+[m[32m    }[m
[32m+[m[32m}[m
[1mdiff --git a/Modules/BrowserTrack/database/migrations/2025_04_08_041408_create_browser_tracks_table.php b/Modules/BrowserTrack/database/migrations/2025_04_08_041408_create_browser_tracks_table.php[m
[1mnew file mode 100644[m
[1mindex 0000000..aab0562[m
[1m--- /dev/null[m
[1m+++ b/Modules/BrowserTrack/database/migrations/2025_04_08_041408_create_browser_tracks_table.php[m
[36m@@ -0,0 +1,32 @@[m
[32m+[m[32m<?php[m
[32m+[m
[32m+[m[32muse Illuminate\Database\Migrations\Migration;[m
[32m+[m[32muse Illuminate\Database\Schema\Blueprint;[m
[32m+[m[32muse Illuminate\Support\Facades\Schema;[m
[32m+[m
[32m+[m[32mreturn new class extends Migration[m
[32m+[m[32m{[m
[32m+[m[32m    /**[m
[32m+[m[32m     * Run the migrations.[m
[32m+[m[32m     */[m
[32m+[m[32m    public function up(): void[m
[32m+[m[32m    {[m
[32m+[m[32m        Schema::create('browser_tracks', function (Blueprint $table) {[m
[32m+[m[32m            $table->id();[m
[32m+[m[32m            $table->foreignId('user_id')->constrained()->onDelete('cascade');[m
[32m+[m[32m            $table->string('browser_name')->nullable();[m
[32m+[m[32m            $table->string('browser_version');[m
[32m+[m[32m            $table->string('os')->nullable();[m
[32m+[m[32m            $table->timestamps();[m
[32m+[m
[32m+[m[32m        });[m
[32m+[m[32m    }[m
[32m+[m
[32m+[m[32m    /**[m
[32m+[m[32m     * Reverse the migrations.[m
[32m+[m[32m     */[m
[32m+[m[32m    public function down(): void[m
[32m+[m[32m    {[m
[32m+[m[32m        Schema::dropIfExists('browser_tracks');[m
[32m+[m[32m    }[m
[32m+[m[32m};[m
[1mdiff --git a/Modules/BrowserTrack/routes/api_v1.0.php b/Modules/BrowserTrack/routes/api_v1.0.php[m
[1mnew file mode 100644[m
[1mindex 0000000..7b78515[m
[1m--- /dev/null[m
[1m+++ b/Modules/BrowserTrack/routes/api_v1.0.php[m
[36m@@ -0,0 +1,7 @@[m
[32m+[m[32m<?php[m
[32m+[m
[32m+[m[32muse Illuminate\Support\Facades\Route;[m
[32m+[m[32muse Modules\BrowserTrack\App\Http\Controllers\BrowserApiController;[m
[32m+[m
[32m+[m[32m// Route::apiResource('page-views', PageViewApiController::class);[m
[32m+[m[32mRoute::apiResource('/browser_track', BrowserApiController::class);[m
[1mdiff --git a/Modules/Contribution/Services/Implementations/Contribution