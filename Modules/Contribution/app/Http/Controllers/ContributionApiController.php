<?php

namespace Modules\Contribution\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Contribution\App\Models\Contribution;
use Modules\Contribution\App\Models\ContributionImage;
use Modules\Users\User\Services\UserApiServiceInterface;
use Modules\Shared\FileUpload\FileUploadServiceInterface;
use Modules\Contribution\Services\ContributionApiServiceInterface;

class ContributionApiController extends Controller
{
    public function __construct(protected ContributionApiServiceInterface $contributionApiService,protected UserApiServiceInterface $userApiService,protected FileUploadServiceInterface $fileUploadService) {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$id)
    {

        $user = $this->userApiService->get($id);

        $request->validate([
            'name'  => 'required',
            'doc_url' => 'required|mimes:doc,docx',
            'image_url' => 'required|array',
            'image_url.*' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        $wordPath = 'upload/doc/';
        $wordFile = $request->file('doc_url');

        if ($wordFile) {
            $wordFilePath = $this->fileUploadService->singleUpload($wordPath, $wordFile);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Word file is required'], 400);
        }

        $contribution = Contribution::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'doc_url' => $wordFilePath,
            'created_by' => $user->id,
        ]);

        $imagePath = 'upload/contribution_image/';
        $imageFiles = $request->file('image_url');
        if ($imageFiles) {
            $imageFilePaths = $this->fileUploadService->multiUpload($imagePath, $imageFiles);
            foreach ($imageFilePaths as $imageFilePath) {
                ContributionImage::create([
                    'contribution_id' => $contribution->id,
                    'image_url' => $imageFilePath,
                ]);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Contribution stored successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
