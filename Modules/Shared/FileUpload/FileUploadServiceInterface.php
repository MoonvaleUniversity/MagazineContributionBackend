<?php

namespace Modules\Shared\FileUpload;

use Illuminate\Http\UploadedFile;

interface FileUploadServiceInterface
{
    /**
     * Handle single file upload.
     *
     * @param string        $path   The upload directory path.
     * @param UploadedFile  $file   Upload file.
     * @param array         $options Additional options:
     *                                  - 'max_size' (int): Maximum allowed file size in bytes (default: no limit).
     *                                  - 'add_unix_time' (bool): Whether to append a Unix timestamp to file names (default: false).
     * @return string URL of an uploaded file.
     */
    public function singleUpload(string $path, $file, array $options = []): string;

    /**
     * Handle multiple file uploads.
     *
     * @param string    $path   The upload directory path.
     * @param array     $files  Array of upload files.
     * @param array     $options Additional options:
     *                              - 'max_size' (int): Maximum allowed file size in bytes (default: no limit).
     *                              - 'add_unix_time' (bool): Whether to append a Unix timestamp to file names (default: false).
     * @return string|array Array of URLs of the uploaded files.
     */
    public function multiUpload(string $path, array $files, array $options = []): array;

    /**
     * Delete file
     *
     * @param string $url The URL of the file to delete.
     * @return bool True if the file was successfully deleted, false otherwise.
     */
    public function delete(string $url);
}
