<?php

namespace Modules\CreativeSpark\Services\Implementations;

use App\Config\Cache\CreativeSparkCache;
use App\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\CreativeSpark\App\Models\CreativeSpark;
use Modules\CreativeSpark\Services\CreativeSparkApiServiceInterface;
use Modules\Shared\FileUpload\FileUploadServiceInterface;

class CreativeSparkApiService implements CreativeSparkApiServiceInterface
{
    public function __construct(protected FileUploadServiceInterface $fileUploadService) {}

    public function get($id = null, $conds = null)
    {
        $readConnection = config('constants.database.read');
        $params = [$id, $conds];
        return Cache::remember(CreativeSparkCache::GET_KEY, CreativeSparkCache::GET_EXPIRY, $params, function () use ($id, $conds, $readConnection) {
            return CreativeSpark::on($readConnection)->when($id, function ($q, $id) {
                $q->where(CreativeSpark::id, $id);
            })->when($conds, function ($q, $conds) {
                $q->where($conds);
            })->first();
        });
    }

    public function getAll($limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null)
    {
        $readConnection = config('constants.database.read');
        $params         = [$limit, $offset, $noPagination, $pagPerPage, $conds];
        return Cache::remember(CreativeSparkCache::GET_ALL_KEY, CreativeSparkCache::GET_ALL_EXPIRY, $params, function () use ($limit, $offset, $noPagination, $pagPerPage, $conds, $readConnection) {
            $creativeSparks = CreativeSpark::on($readConnection)
                ->when($limit, function ($q, $limit) {
                    $q->limit($limit);
                })
                ->when($offset, function ($q, $offset) {
                    $q->offset($offset);
                })->when($conds, function ($q, $conds) {
                    $q->where($conds);
                });

            if (($noPagination !== null && !$noPagination) || $pagPerPage) {
                return $creativeSparks->paginate($pagPerPage ?? config('constants.pagPerPage'));
            } else {
                return $creativeSparks->get();
            }
        });
    }

    public function create($creativeSparkData, $imageFile)
    {
        //write db connection
        DB::beginTransaction();
        try {
            $creativeSparkData[CreativeSpark::image_url] = $this->fileUploadService->singleUpload(config('creative-spark.upload_path'), $imageFile, ['add_unix_time' => true]);

            $creativeSpark = $this->createCreativeSpark($creativeSparkData);

            DB::commit();
            Cache::clear([CreativeSparkCache::GET_KEY, CreativeSparkCache::GET_ALL_KEY]);

            return $creativeSparkData;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $creativeSparkData, $imageFile = null)
    {
        DB::beginTransaction();
        try {
            if ($imageFile) {
                $creativeSpark = $this->get($id);
                $this->fileUploadService->delete($creativeSpark[CreativeSpark::image_url]);
                $creativeSparkData[CreativeSpark::image_url] = $this->fileUploadService->singleUpload(config('creative-spark.upload_path'), $imageFile, ['add_unix_time' => true]);
            }

            $creativeSpark = $this->updateCreativeSpark($id, $creativeSparkData);

            DB::commit();
            Cache::clear([CreativeSparkCache::GET_KEY, CreativeSparkCache::GET_ALL_KEY]);

            return $creativeSpark;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $creativeSpark = $this->get($id);
            $this->fileUploadService->delete($creativeSpark[CreativeSpark::image_url]);
            $title = $this->deleteCreativeSpark($id);

            DB::commit();
            Cache::clear([CreativeSparkCache::GET_KEY, CreativeSparkCache::GET_ALL_KEY]);

            return $title;
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

    private function createCreativeSpark($creativeSparkData)
    {
        $creativeSpark = new CreativeSpark();
        $creativeSpark->fill($creativeSparkData);
        $creativeSpark->save();

        return $creativeSpark;
    }

    private function updateCreativeSpark($id, $creativeSparkData)
    {
        $creativeSpark = $this->get($id);
        $creativeSpark->update($creativeSparkData);
        $creativeSpark->save();

        return $creativeSpark;
    }

    private function deleteCreativeSpark($id)
    {
        $creativeSpark = $this->get($id);
        $title = $creativeSpark->title;
        $creativeSpark->delete();

        return $title;
    }
}
