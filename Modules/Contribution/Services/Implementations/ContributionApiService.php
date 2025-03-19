<?php

namespace Modules\Contribution\Services\Implementations;

use App\Config\Cache\ContributionCache;
use App\Facades\Cache;
use Modules\Contribution\App\Models\Contribution;
use Modules\Contribution\Services\ContributionApiServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Modules\AcademicYear\App\Models\AcademicYear;
use Modules\ClosureDate\App\Models\ClosureDate;
use Modules\Shared\FileUpload\FileUploadServiceInterface;
use Modules\Users\User\App\Models\User;
use Modules\AcademicYear\Services\AcademicYearApiServiceInterface;
use Modules\Contribution\App\Models\ContributionImage;
use Modules\Faculty\App\Models\Faculty;
use Modules\Faculty\Services\FacultyApiServiceInterface;
use Modules\Shared\Email\EmailServiceInterface;
use Modules\Users\User\Services\UserApiServiceInterface;

class ContributionApiService implements ContributionApiServiceInterface
{
    public function __construct(protected FileUploadServiceInterface $fileUploadService, protected AcademicYearApiServiceInterface $academicYearApiService, protected FacultyApiServiceInterface $facultyApiService, protected UserApiServiceInterface $userApiService, protected EmailServiceInterface $emailService) {}

    public function get($id = null, $relations = null, $conds = null)
    {
        //read db connection
        $readConnection = config('constants.database.read');
        $params = [$id, $relations, $conds];
        return Cache::remember(ContributionCache::GET_KEY, ContributionCache::GET_EXPIRY, $params, function () use ($id, $relations, $conds, $readConnection) {
            return Contribution::on($readConnection)
                ->when($id, function ($q, $id) {
                    $q->where(Contribution::id, $id);
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
        return Cache::remember(ContributionCache::GET_ALL_KEY, ContributionCache::GET_ALL_EXPIRY, $params, function () use ($relations, $limit, $offset, $noPagination, $pagPerPage, $conds, $readConnection) {
            $contributions = Contribution::on($readConnection)
                ->when($relations, function ($q, $relations) {
                    $q->with($relations);
                })
                ->when($limit, function ($q, $limit) {
                    $q->limit($limit);
                })
                ->when($offset, function ($q, $offset) {
                    $q->offset($offset);
                })
                ->when($conds, function ($q, $conds) {
                    $this->searching($q, $conds);
                });

            $contributions = (!$noPagination || $pagPerPage) ? $contributions->paginate($pagPerPage) : $contributions->get();

            return $contributions;
        });
    }

    public function create($contributionData, $wordFile, $imageFiles)
    {
        //write db connection
        DB::beginTransaction();
        try {
            //Generate Upload Path
            $academicYear = $this->academicYearApiService->get(conds: ['closure_date_id@@id' => $contributionData['closure_date_id']]);
            $faculty = $this->facultyApiService->get(conds: ['student_id@@id' => $contributionData['user_id']]);
            $student = $this->userApiService->get($contributionData['user_id']);
            $uploadPath = $this->generateUploadPath($academicYear, $faculty, $student, $contributionData['name']);


            //Upload Word File
            $contributionData[Contribution::doc_url] = $this->fileUploadService->singleUpload($uploadPath, $wordFile, ['add_unix_time' => true]);

            //Create Contribution
            $contribution = $this->createContribution($contributionData);

            //Upload Images File
            $imageURLs = $this->fileUploadService->multiUpload($uploadPath, $imageFiles);
            $this->createContributionImages($contribution, $imageURLs);


            DB::commit();
            Cache::clear([ContributionCache::GET_KEY, ContributionCache::GET_ALL_KEY]);

            return $contribution;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update()
    {
        //write db connection
    }

    public function delete()
    {
        //write db connection
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------
    private function createContribution($contributionData)
    {
        $contribution = new Contribution();
        $contribution->fill($contributionData);
        $contribution->save();

        return $contribution;
    }

    private function createContributionImages(Contribution $contribution, array $imageURLs): void
    {
        $imageRecords = array_map(fn($url) => [ContributionImage::image_url => $url], $imageURLs);
        $contribution->images()->createMany($imageRecords);
    }

    private function searching(Builder $query, $conds)
    {
        $query
            ->when(isset($conds['name']), function ($q) use ($conds) {
                $q->where(Contribution::name, $conds['name']);
            })
            ->when(isset($conds['user_id']), function ($q) use ($conds) {
                $q->where(Contribution::user_id, $conds['user_id']);
            })
            ->when(isset($conds['user_id@@name']), function ($q) use ($conds) {
                $q->whereHas('user', function ($q) use ($conds) {
                    $q->where(User::name, $conds['user_id@@name']);
                });
            })
            ->when(isset($conds['user_id@@academic_year_id']), function ($q) use ($conds) {
                $q->whereHas('user', function ($q) use ($conds) {
                    $q->where(User::academic_year_id, $conds['user_id@@academic_year_id']);
                });
            })
            ->when(isset($conds['closure_date_id']), function ($q) use ($conds) {
                $q->where(Contribution::closure_date_id, $conds['closure_date_id']);
            })
            ->when(isset($conds['closure_date_id@@academic_year_id']), function ($q) use ($conds) {
                $q->whereHas('closure_date', function ($q) use ($conds) {
                    $q->where(ClosureDate::academic_year_id, $conds['closure_date_id@@academic_year_id']);
                });
            })
            ->when(isset($conds['is_selected_for_publication']), function ($q) use ($conds) {
                $q->where(Contribution::is_selected_for_publication, $conds['is_selected_for_publication']);
            });

        return $query;
    }
    //-------------------------------------------------------------------
    // Others
    //-------------------------------------------------------------------
    private function generateUploadPath(AcademicYear $academicYear, Faculty $faculty, User $student, string $contributionName): string
    {
        $baseUploadPath = config('contribution.upload_path');

        // Optional: Sanitize folder names to avoid issues (spaces, special characters)
        $safeFacultyName = $this->sanitizePathComponent($faculty->name);
        $safeStudentName = $this->sanitizePathComponent($student->name);
        $safeContributionName = $this->sanitizePathComponent($contributionName);

        return sprintf(
            '%s/%s/%s/%s/%s',
            rtrim($baseUploadPath, '/'),
            $academicYear->year_name,
            $safeFacultyName,
            $safeStudentName,
            $safeContributionName
        );
    }

    private function sanitizePathComponent(string $name): string
    {
        return preg_replace('/[^a-zA-Z0-9_-]/', '_', $name); // replace invalid characters
    }
}
