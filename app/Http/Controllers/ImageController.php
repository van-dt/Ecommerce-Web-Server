<?php

namespace App\Http\Controllers;
use App\Models\Image;
use App\Http\Requests\StoreImage;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    private $image;
    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    public function postUpload(StoreImage $request)
    {
        $path = Storage::disk('s3')->put('images/originals', $request->file, 'public');
        $request->merge([
            'size' => $request->file->getSize(),
            'path' => $path
        ]);
        $this->image->create($request->only('path', 'title', 'size'));
        return response()->json([
            'success' => true,
            'message' => 'Image Successfully Saved',
            'pathImage' =>  env('AWS_S3_URL').'/'.$path
        ]);
    }

    public function destroy(Image $image)
    {
        // $path = $image->path;
        $image->delete();
        Storage::disk('s3')->delete($image->path);
        return response()->json([
            'success' => true,
            'message' => 'Image Successfully Deleted',
        ]);
    }
}
