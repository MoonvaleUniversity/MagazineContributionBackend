<?php

namespace Modules\Shared\FileManagementService\Services\Implementations;

use Modules\Shared\FileManagementService\Services\FileManagementApiServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class FileManagementApiService implements FileManagementApiServiceInterface
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = "http://127.0.0.1:8080/api";
    }

    public function singleUpload(string $uploadPath, UploadedFile $file): string
    {
        $response = Http::attach(
            'file',
            file_get_contents($file),
            $file->getClientOriginalName()
        )->post("{$this->baseUrl}/upload-file", [
            'uploadPath' => $uploadPath,
        ]);

        if ($response->successful()) {
            return $response->json('url');
        }

        throw new \Exception("File upload failed: " . $response->body());
    }

    public function multiUpload(string $uploadPath, array $files): array
    {
        $request = Http::asMultipart();

        foreach ($files as $file) {
            $request->attach(
                'file[]',
                file_get_contents($file),
                $file->getClientOriginalName()
            );
        }

        $response = $request->post("{$this->baseUrl}/upload-file", [
            'uploadPath' => $uploadPath,
        ]);

        if ($response->successful()) {
            return $response->json('url');
        }

        throw new \Exception("Multiple file upload failed: " . $response->body());
    }

    public function delete(string $url)
    {
        $response = Http::post("{$this->baseUrl}/delete-file", [
            'file_url' => $url,
        ]);

        if ($response->successful()) {
            return $response->json('success');
        }

        throw new \Exception("Multiple file upload failed: " . $response->body());
    }
}
