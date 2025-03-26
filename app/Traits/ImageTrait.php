<?php

namespace App\Traits;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

trait ImageTrait
{
    public function imageUpload($request, $file, $imagename, $uploadfolder, $formImage) //formImage for validation
    {
        if ($request->session()->has('ajaximage')) {
            $image = $request->session()->get('ajaximage');
            @unlink('uploads/' . $uploadfolder . '/' . $image);
            @unlink('uploads/' . $uploadfolder . '/' . 'thumbnail_' . $image);
        }
        $v = Validator::make([$formImage => request()->file($formImage)], [
            $formImage => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);
        if ($v->fails()) {
            $response = array(
                'status' => 'error',
                'message' => $v->getMessageBag()->toArray()
            );
        } else {
            if ($file != null) {
                $image_name = "$imagename" . time() . "." . $file->clientExtension();
                session(['ajaximage' => $image_name]);

                $dir = public_path('uploads/' . $uploadfolder);

                if (!File::exists($dir)) {
                    File::makeDirectory($dir);
                }

                // open an image file
                $img = Image::make($file);
                $original_path  = $dir . '/' . $image_name;
                $img->save($original_path);
                $path = $dir . '/thumbnail_' . $image_name;
                $img->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path);
                // save image in desired format


                // return $image_name;
            }
            $response = array(
                'status' => 'success',
                'message' => 'Image Uploaded Successfully!'
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
