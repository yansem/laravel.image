<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\UpdateRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Image;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class UpdateController extends Controller
{
    public function __invoke(Post $post, UpdateRequest $request)
    {
        $data = $request->validated();
        $imageIdsForDelete = $data['imageIdsForDelete'] ?? null;
        $newImages = $data['images'] ?? null;
        $currentImages = $post->images ?? null;
        if ($imageIdsForDelete) {
            unset($data['imageIdsForDelete']);
            foreach ($currentImages as $currentImage) {
                if (in_array($currentImage->id, $imageIdsForDelete)) {
                    Storage::disk('public')->delete($currentImage->path);
                    Storage::disk('public')->delete(str_replace('images/', 'images/prev_', $currentImage->path));
                    $currentImage->delete();
                }
            }
        }
        if ($newImages) {
            unset($data['images']);
            foreach ($newImages as $image) {
                $name = md5(Carbon::now() . '_' . $image->getClientOriginalName()) . '.' .
                    $image->getClientOriginalExtension();
                $previewName = 'prev_' . $name;
                $filePath = Storage::disk('public')->putFileAs('/images', $image, $name);
                \Intervention\Image\Facades\Image::make($image)->fit(100, 100)->save(storage_path('app/public/images/' .
                    $previewName));
                Image::create([
                    'path' => $filePath,
                    'url' => url('/storage/' . $filePath),
                    'preview_url' => url('/storage/images/' . $previewName),
                    'post_id' => $post->id
                ]);
            }

        }
        $post->update($data);
        return new PostResource($post);
    }
}
