<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * @param UploadRequest $request
     * @return JsonResponse|void
     */
    public function upload(UploadRequest $request)
    {
        if ($request->file('uploadFile')->isValid()) {
            $file = $request->file('uploadFile');
            $path = $request->file('uploadFile')->path();
            $extension = $request->file('uploadFile')->extension();
            $fileNameWithExtension = $file->getClientOriginalName();
            $fileNameWithExtension = $request->userId . '-' . time() . '.' . $extension;

            $path = $request->file('uploadFile')->storeAs('uploads/image', $fileNameWithExtension, 'public');


            return response()->json(['url' => asset("storage/$path")]);

        }
    }
}
