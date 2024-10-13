<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\StoreRequest;
use App\Http\Requests\Profile\UpdateRequest;
use App\Http\Resources\Profile\ProfileResource;
use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    public function show(User $user): array|HttpResponse
    {
        $profile = $user->profile;

        return $profile
            ? ProfileResource::make($profile)->resolve()
            : response([], Response::HTTP_NOT_FOUND);
    }

    public function store(User $user, StoreRequest $request, ProfileService $profileService): HttpResponse
    {
        $data = $request->validated();

        $profile = $profileService->store($user, $data);

        if ($profile) {
            return response([
                'data' => ProfileResource::make($profile)->resolve(),
            ], Response::HTTP_CREATED);
        } else {
            return response([
                'error' => 'Server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(User $user, UpdateRequest $request, ProfileService $profileService): HttpResponse
    {
        $data = $request->validated();
        $isUpdated = $profileService->update($user->profile, $data);

        if ($isUpdated) {
            return response([
                'updated' => true,
            ], Response::HTTP_OK);
        } else {
            return response([
                'error' => 'Server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(User $user, ProfileService $profileService): HttpResponse
    {
        $profileService->destroy($user->profile);

        return response([
            'deleted' => true,
        ], Response::HTTP_OK);
    }
}
