<?php

namespace App\Http\Controllers\Proxy;

use App\Http\Controllers\Controller;
use App\Services\Proxy\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProfileController extends Controller
{
    public function __construct(
        private ProfileService $profileService
    ) {}

    public function store(Request $request): Response|JsonResponse
    {
        return $this->profileService->store($request);
    }

    public function show(): Response|JsonResponse
    {
        return $this->profileService->getProfile();
    }

    public function update(Request $request): Response|JsonResponse
    {
        return $this->profileService->update($request);
    }

    public function destroy(): Response|JsonResponse
    {
        return $this->profileService->destroy();
    }
}
