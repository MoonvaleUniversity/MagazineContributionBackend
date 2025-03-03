<?php

namespace Modules\Shared\FileUpload;

use Illuminate\Support\Facades\Storage;

class FileUploadService implements FileUploadServiceInterface
{
    protected string $disk;

    public function __construct()
    {
        // Default to 'public' if not set in .env
        $this->disk = env('DEFAULT_STORAGE', 'public');
    }

    public function setDisk(string $disk): void
    {
        $this->disk = $disk;
    }

    public function singleUpload(string $path, $file, array $options = []): string
    {
        $name = $file->getClientOriginalName();

        // Validate the file parameter
        if (!$file || !is_file($file)) {
            throw new \InvalidArgumentException('Invalid file provided for upload.');
        }

        // Validate file size if maxSize is provided
        if (isset($options['max_size']) && filesize($file) > $options['max_size']) {
            throw new \InvalidArgumentException('File size exceeds the maximum allowed size of ' . $options['max_size'] . ' bytes.');
        }

        // Combine the path and name to create a full file path
        if (isset($options['add_unix_time']) && $options['add_unix_time']) $name = now()->unix() . "_" . $name;
        $fullFilePath = rtrim($path, '/') . '/' . $name;

        // Ensure the directory exists (create if necessary)
        if (!Storage::disk($this->disk)->exists($path)) {
            Storage::disk($this->disk)->makeDirectory($path);
        }

        // ** Need to check if the file already exist depending on the disk (to be added)**

        // Store the file using Laravel's Storage facade
        $storedFilePath = Storage::disk($this->disk)->put($fullFilePath, file_get_contents($file));

        if (!$storedFilePath) {
            throw new \RuntimeException('Failed to upload file to storage.');
        }

        // Depending on the disk, add url
        if ($this->disk === "public") {
            $fullStoredFilePath = env("APP_URL") . "/storage/" . ltrim($fullFilePath, "/");
        }

        // **Need to add more condition for the disk (to be added)**

        // Return the path where the file is stored
        return $fullStoredFilePath;
    }

    public function multiUpload(string $path, array $files, array $options = []): array
    {
        $filePaths = [];

        foreach ($files as $file) {

            $uploadedPath = $this->singleUpload($path, $file, $options);

            $filePaths[] = $uploadedPath;
        }

        return $filePaths;
    }

    public function delete(string $url): bool
    {
        $filePath = parse_url($url)['path'];
        if ($this->disk === 'public') {
            $filePath = str_replace('/storage', '', $filePath);
        }
        return Storage::disk($this->disk)->delete($filePath);
    }
}
