<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('title', $request->query('search'));

        $media = Media::with('users')
            ->when(
                is_string($search) && $search !== '',
                fn ($query) => $query->where('title', 'like', '%'.$search.'%')
            )
            ->orderBy('id')
            ->get();

        return MediaResource::collection($media)->response()->setStatusCode(200);
    }

    public function show(Media $media)
    {
        return (new MediaResource($media->load('users')))->response()->setStatusCode(200);
    }

    public function store(StoreMediaRequest $request)
    {
        $media = Media::create($request->validated());

        return (new MediaResource($media))->response()->setStatusCode(201);
    }

    public function update(StoreMediaRequest $request, Media $media)
    {
        $media->update($request->validated());

        return (new MediaResource($media->refresh()))->response()->setStatusCode(200);
    }

    public function destroy(Media $media)
    {
        $media->delete();

        return response()->json(['message' => 'Media berhasil dihapus'], 200);
    }
}
