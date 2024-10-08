<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProfileService
{
    public function store(User $user, array $data): ?Profile
    {
        try {
            if (isset($data['image']) && $data['image']) {
                $data['image'] = Storage::put('images/profiles', $data['image']);
            }

            $profile = $user->profile()->create($data);

            return $profile;
        } catch (Exception $e) {
            return $this->handleExceptionForStoringOrUpdating($e, $data);
        }
    }

    public function update(Profile $profile, array $data): ?bool
    {
        try {
            if (! $profile) {
                return response([], Response::HTTP_NOT_FOUND);
            }

            if (isset($data['image']) && $data['image']) {
                Storage::delete($profile->image);
                $data['image'] = Storage::put('images/profiles', $data['image']);
            }

            return $profile->update($data);
        } catch (Exception $e) {
            return $this->handleExceptionForStoringOrUpdating($e, $data);
        }
    }

    public function destroy(Profile $profile): void
    {
        if ($profile->image) {
            Storage::delete($profile->image);
        }

        $profile->delete();
    }

    private function handleExceptionForStoringOrUpdating(Exception $e, array $data): ?bool
    {
        Log::error($e->getMessage());

        if (
            isset($data['image'])
            && is_string($data['image'])
            && Storage::fileExists($data['image'])
        ) {
            Storage::delete($data['image']);
        }

        return null;
    }
}
