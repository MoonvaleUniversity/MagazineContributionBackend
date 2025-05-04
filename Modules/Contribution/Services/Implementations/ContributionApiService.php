<?php

namespace Modules\Contribution\Services\Implementations;

use App\Config\Cache\ContributionCache;
use App\Config\Cache\UserCache;
use App\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\AcademicYear\App\Models\AcademicYear;
use Modules\AcademicYear\Services\AcademicYearApiServiceInterface;
use Modules\ClosureDate\App\Models\ClosureDate;
use Modules\Contribution\App\Models\Contribution;
use Modules\Contribution\App\Models\ContributionImage;
use Modules\Contribution\Services\ContributionApiServiceInterface;
use Modules\Faculty\App\Models\Faculty;
use Modules\Faculty\Services\FacultyApiServiceInterface;
use Modules\Shared\Email\EmailServiceInterface;
use Modules\Shared\FileManagementService\Services\FileManagementApiServiceInterface;
use Modules\Shared\FileUpload\FileUploadServiceInterface;
use Modules\Shared\ZipFile\ZipFileServiceInterface;
use Modules\Users\User\App\Models\User;
use Modules\Users\User\Services\UserApiServiceInterface;
use ZipArchive;

class ContributionApiService implements ContributionApiServiceInterface
{
    public function __construct(
        protected FileUploadServiceInterface $fileUploadService,
        protected AcademicYearApiServiceInterface $academicYearApiService,
        protected FacultyApiServiceInterface $facultyApiService,
        protected UserApiServiceInterface $userApiService,
        protected EmailServiceInterface $emailService,
        protected FileManagementApiServiceInterface $fileManagementApiService
    ) {}

    public function get($id = null, $relations = null, $conds = null)
    {
        //read db connection
        $readConnection = config('constants.database.read');
        $params         = [$id, $relations, $conds];
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
        $params         = [$relations, $limit, $offset, $noPagination, $pagPerPage, $conds];
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

            if (($noPagination !== null && !$noPagination) || $pagPerPage) {
                return $contributions->paginate($pagPerPage ?? config('constants.pagPerPage'));
            } else {
                return $contributions->get();
            }
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
            $contributionData[Contribution::doc_url] = $this->fileManagementApiService->singleUpload($uploadPath, $wordFile);

            //Create Contribution
            $contribution = $this->createContribution($contributionData);

            //Upload Images File
            $imageURLs = $this->fileManagementApiService->multiUpload($uploadPath, $imageFiles);
            $this->createContributionImages($contribution, $imageURLs);


            DB::commit();
            Cache::clear([ContributionCache::GET_KEY, ContributionCache::GET_ALL_KEY]);

            return $contribution;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function automatic()
    {
        DB::beginTransaction();

        try {
            $timer = now()->subMinutes(5);
            $timer = now()->subMinutes(5);
            $contributions = Contribution::where('created_at', '<=', $timer)->get();

            foreach ($contributions as $contribution) {
                $commentCount = DB::table('comments')
                    ->where('contribution_id', $contribution->id)
                    ->count();
                if ($commentCount == 0) {
                    $user = User::find($contribution->user_id);
                    $marketingCoordinator = $this->userApiService->getAll(
                        conds: [
                            'faculty_id' => $user->faculty_id
                        ],
                        relations: ['roles']
                    )
                        ->filter(function ($user) {
                            return $user->roles->contains('name', 'Marketing Coordinator');
                        })->first();
                    if ($commentCount == 0) {
                        $user                 = User::find($contribution->user_id);
                        $marketingCoordinator = $this->userApiService->getAll(
                            conds: [
                                'faculty_id' => $user->faculty_id,
                            ],
                            relations: ['roles']
                        )
                            ->filter(function ($user) {
                                return $user->roles->contains('name', 'Marketing Coordinator');
                            })->first();
                        $user = User::find($contribution->user_id);
                        if (! $user) {
                            return apiResponse(false, 'Marketing coordinator not found for this faculty.');
                        }
                        $this->emailService->send('reminder-email', $marketingCoordinator->email, 'Your contribution has not received any comments.', ['contribution' => $contribution]);
                    }
                }
            }
            DB::commit();
            Cache::clear([ContributionCache::GET_KEY, ContributionCache::GET_ALL_KEY]);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $contributionData, $wordFile = null, $imageFiles = null)
    {
        //write db connection
        DB::beginTransaction();
        try {
            $contribution = $this->get($id);

            if (!$contribution) {
                throw new \Exception("Contribution not found.");
            }

            //Generate Upload Path
            $academicYear = $this->academicYearApiService->get(conds: ['closure_date_id@@id' => $contributionData['closure_date_id']]);
            $faculty = $this->facultyApiService->get(conds: ['student_id@@id' => $contributionData['user_id']]);
            $student = $this->userApiService->get($contributionData['user_id']);
            $uploadPath = $this->generateUploadPath($academicYear, $faculty, $student, $contributionData['name']);

            if (isset($wordFile) && $wordFile) {
                $contributionData[Contribution::doc_url] = $this->fileManagementApiService->singleUpload($uploadPath, $wordFile);
            }

            if (isset($contributionData['delete_images']) && count($contributionData['delete_images']) > 0) {
                $contributionImages = $contribution->images()->whereIn('id', $contributionData['delete_images'])->pluck('image_url')->toArray();
                foreach ($contributionImages as $image) {
                    $this->fileManagementApiService->delete($image);
                }
                $contribution->images()->whereIn('id', $contributionData['delete_images'])->delete();
            }

            $this->updateContribution($id, $contributionData);

            if(isset($imageFiles) && count($imageFiles) > 0) {
                $imageURLs = $this->fileManagementApiService->multiUpload($uploadPath, $imageFiles);
                $this->createContributionImages($contribution, $imageURLs);
            }

            DB::commit();
            Cache::clear([ContributionCache::GET_KEY, ContributionCache::GET_ALL_KEY]);

            return $contribution;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updatePublish($id)
    {
        DB::beginTransaction();
        try {

            $contribution = Contribution::where('id', $id)->update(['is_selected_for_publication' => 1]);
            DB::commit();
            Cache::clear([ContributionCache::GET_KEY, ContributionCache::GET_ALL_KEY]);

            return $contribution;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function downloadZip($id)
    {
        DB::beginTransaction();
        try {
            $contribution = Contribution::findOrFail($id);

            if ($contribution->is_selected_for_publication != 1) {
                return apiResponse(false, 'Contribution not approved for publication. Download not allowed.', [], 404);
            }

            $relativePath = Str::after($contribution->doc_url, '/storage/');
            $filePath     = storage_path("app/public/{$relativePath}");

            if (!file_exists($filePath)) {
                return apiResponse(false, 'File not found.', ['file_path' => $filePath], 404);
            }

            // Create a temporary zip file
            $zipFileName = 'Moon_Vale_' . $contribution->name . '.zip';
            $zipFilePath = storage_path("app/public/{$zipFileName}");

            $zip = new ZipArchive;
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                $zip->addFile($filePath, basename($filePath));
                $zip->close();
            } else {
                return apiResponse(false, 'Could not create zip file.', [], 500);
            }
            DB::commit();
            return response()->download($zipFilePath, $zipFileName, [
                'Content-Type' => 'application/zip',
            ])->deleteFileAfterSend(shouldDelete: true);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function delete($id)
    {
        //write db connection
        DB::beginTransaction();
        try {
            $imageUrls = $this->deleteContributionImages($id);
            [$name, $docUrl] = $this->deleteContribution($id);
            Cache::clear([ContributionCache::GET_ALL_KEY, ContributionCache::GET_KEY]);
            DB::commit();
            foreach ($imageUrls as $url) {
                $this->fileUploadService->delete($url);
            }
            $this->fileUploadService->delete($docUrl);
            return $name;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function comment($id, $userId, $content)
    {
        DB::beginTransaction();
        try {
            $contribution = $this->get($id);
            $contribution->user_comments()->syncWithoutDetaching([$userId => ['content' => $content, 'created_at' => now(), 'updated_at' => now()]]);

            Cache::clear([ContributionCache::GET_ALL_KEY, ContributionCache::GET_KEY, UserCache::GET_ALL_EXPIRY, UserCache::GET_KEY]);
            DB::commit();

            return $contribution;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteComment($id, $userId)
    {
        DB::beginTransaction();
        try {
            $contribution = $this->get($id);
            $contribution->user_comments()->detach($userId);

            Cache::clear([ContributionCache::GET_ALL_KEY, ContributionCache::GET_KEY, UserCache::GET_ALL_EXPIRY, UserCache::GET_KEY]);
            DB::commit();

            return $contribution;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function vote($id, $userId, $voteType)
    {
        DB::beginTransaction();
        try {
            $contribution = $this->get($id);
            $existingVote  = $contribution->user_votes()->wherePivot('user_id', $userId)->wherePivot('type', $voteType)->first();
            if ($existingVote) {
                $contribution->user_votes()->detach($userId);
            } else {
                $contribution->user_votes()->syncWithoutDetaching([$userId => ['type' => $voteType]]);
            }

            Cache::clear([ContributionCache::GET_ALL_KEY, ContributionCache::GET_KEY, UserCache::GET_ALL_EXPIRY, UserCache::GET_KEY]);
            DB::commit();

            return $contribution;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function save($id, $userId)
    {
        DB::beginTransaction();
        try {
            $contribution = $this->get($id);
            $existingSave = $contribution->saved_users()->wherePivot('user_id', $userId)->first();
            if ($existingSave) {
                $contribution->saved_users()->detach($userId);
            } else {
                $contribution->saved_users()->attach($userId);
            }

            Cache::clear([ContributionCache::GET_ALL_KEY, ContributionCache::GET_KEY, UserCache::GET_ALL_EXPIRY, UserCache::GET_KEY]);
            DB::commit();

            return $contribution;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
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

    private function updateContribution($id, $contributionData)
    {
        $contribution = $this->get($id);
        $contribution->fill($contributionData);
        $contribution->save();

        return $contribution;
    }

    private function deleteContribution($id)
    {
        $this->removeForeignTableData($id);
        $contribution = $this->get($id);
        $name = $contribution->name;
        $docUrl = $contribution->doc_url;
        $contribution->delete();

        return [$name, $docUrl];
    }


    private function removeForeignTableData($id)
    {
        $contribution = $this->get($id);
        $contribution->user_comments()->detach();
        $contribution->user_votes()->detach();
        $contribution->saved_users()->detach();
    }

    private function deleteContributionImages($id)
    {
        $contribution = $this->get($id, ['images']);
        $imageUrls = $contribution->images->pluck('image_url');
        $contribution->images()->delete();
        return $imageUrls;
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
            ->when(isset($conds['user_id@@faculty_id']), function ($q) use ($conds) {
                $q->whereHas('user', function ($q) use ($conds) {
                    $q->where(User::faculty_id, $conds['user_id@@faculty_id']);
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
        $safeFacultyName      = $this->sanitizePathComponent($faculty->name);
        $safeStudentName      = $this->sanitizePathComponent($student->name);
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
