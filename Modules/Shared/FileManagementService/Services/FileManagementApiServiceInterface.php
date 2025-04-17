<?php

namespace Modules\Shared\FileManagementService\Services;

use Illuminate\Http\UploadedFile;

interface FileManagementApiServiceInterface
{
    public function singleUpload(string $uploadPath, UploadedFile $file);

    public function multiUpload(string $uploadPath, array $files);
}
