<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class UploadImageService
{
    public function uploadImage($request, $model, $uploadPath)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            if ($file->isValid()) {
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $fileName);
                $model->image = $fileName;
            } else {
                Log::error('File upload is not valid.');
                return false;
            }
        } elseif ($request->filled('oldImage')) {
            $base64Image = $request->input('oldImage');
            $fileName = time() . '.jpg';

            list($type, $base64Image) = explode(';', $base64Image);
            list(, $base64Image) = explode(',', $base64Image);

            $imageData = base64_decode($base64Image);
            $filePath = $uploadPath . '/' . $fileName;

            if (file_put_contents($filePath, $imageData)) {
                $model->image = $fileName;
            } else {
                Log::error('Failed to save base64 image.');
                return false;
            }
        }

        return $model->save();
    }
}
