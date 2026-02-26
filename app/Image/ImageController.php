<?php

namespace App\Image;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageController extends Controller
{
    use AuthorizesRequests;

    public function store(string $document, int $id, Request $request, ImageableResolver $resolver, ImageService $service)
    {
        $request->validate([
            "images" => ['required', 'array', 'min:1', 'max:10'],
            "images.*" => "required|image|mimes:jpeg,png,jpg,svg,webp|max:2048"
        ]);

        $model = $resolver->resolve($document);

        $model_instance = $model::findOrFail($id);

        $this->authorize('update', $model_instance);

        foreach ($request['images'] as $image){
            $service->persist($model_instance, $image, $document);
        }

        return response()->json([
            'message' => __('documents.image.upload_success')
        ], 200);
    }

    public function destroy(Image $image, ImageService $service)
    {
        if (!$image->imageable)
        {
            throw new NotFoundHttpException;
        }

        $this->authorize('update', $image->imageable);

        $service->delete($image);

        return response()->noContent();
    }
}
