<?php

namespace App\Services\Attachment;

use Illuminate\Support\Facades\Storage;
use function abort;
use function processBase64Image;
use function response;
use function responseByStatus;
use function storage_path;
use function url;

class FileService
{
    private const BASE64 = 'base64';

    public function upload($file, $path = 'uploads', $maxSize = 1, $diskName = 'public')
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $file, $type)) {
            list($imageData, $filename) = processBase64Image($file, $type[1]);

            if (strlen($imageData) > ($maxSize * 1024 * 1024)) {
                return responseByStatus(false, 'File size exceeds ');
            }

            return $this->storeBase64( $diskName, "$path/$filename", $imageData);
        }

        return $this->store($file, $diskName, $path);
    }

    protected function storeBase64($diskName, $path, $imageData)
    {
        Storage::disk($diskName)->put($path, $imageData);
        return url('storage/' . $path);
    }

    protected function store($file, $diskName, $path)
    {
        $path = $file->store($path, $diskName);
        return url('storage/' . $path);
    }

    public function delete($path, $diskName = 'public')
    {
        $path = str_replace('/storage/', '', parse_url($path, PHP_URL_PATH));
        // Check if the file exists
        if (Storage::disk($diskName)->exists($path)) {
            // Delete the file
            Storage::disk($diskName)->delete($path);
            return true;
        }
        return false;
    }

    public function download($path, $prefix = 'app/')
    {
        if (!Storage::exists($path)) {
            abort(404, 'File not found');
        }
        $path = storage_path($prefix . $path);

        return response()->download($path);
    }

    public function convertImageToBase64($filePath, $disk = 'public') {
        if (!Storage::disk($disk)->exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $fileContent = Storage::disk($disk)->get($filePath);
        $mimeType = Storage::disk($disk)->mimeType($filePath);

        return 'data:' . $mimeType . ';base64,' . base64_encode($fileContent);
    }
}
