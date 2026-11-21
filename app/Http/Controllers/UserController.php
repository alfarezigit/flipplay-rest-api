<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::with('media')->orderBy('id')->get())
            ->response()
            ->setStatusCode(200);
    }

    public function show(User $user)
    {
        return (new UserResource($user->load('media')))->response()->setStatusCode(200);
    }

    public function addToCollection(Request $request, User $user)
    {
        $validated = $request->validate([
            'media_id' => ['required', 'exists:media,id'],
        ], [
            'media_id.required' => 'ID media wajib diisi.',
            'media_id.exists' => 'Media tidak ditemukan.',
        ]);

        if ($user->media()->where('media_id', $validated['media_id'])->exists()) {
            return response()->json(['message' => 'Media sudah ada di koleksi'], 409);
        }

        $user->media()->attach($validated['media_id']);

        return response()->json([
            'message' => 'Media berhasil ditambahkan ke koleksi',
            'data' => new UserResource($user->load('media')),
        ], 200);
    }

    public function removeFromCollection(User $user, Media $media)
    {
        if (! $user->media()->whereKey($media->id)->exists()) {
            return response()->json(['message' => 'Media tidak ditemukan di koleksi'], 404);
        }

        $user->media()->detach($media->id);

        return response()->json(['message' => 'Media berhasil dihapus dari koleksi'], 200);
    }
}
