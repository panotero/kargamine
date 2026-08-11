<?php

namespace App\Http\Controllers;

use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function show()
    {
        return response()->json([
            'success' => true,
            'data' => Auth::user(),
        ]);
    }

    // Name only - email is intentionally not accepted here.
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = Auth::user();
        $user->update(['name' => $validated['name']]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => $user,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }

    public function uploadPhoto(Request $request)
    {
        $validated = $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $user = Auth::user();

        $urls = $this->fileUploadService->uploadFile([$validated['photo']], 'uploads/profile-photos');
        $url = $urls[0] ?? null;

        if (!$url) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to upload the photo.',
            ], 422);
        }

        $oldPath = $user->profile_photo_path;

        $user->update(['profile_photo_path' => $url]);

        if ($oldPath) {
            $this->fileUploadService->deleteFile($oldPath);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile photo updated.',
            'data' => $user,
        ]);
    }

    public function deletePhoto()
    {
        $user = Auth::user();

        if ($user->profile_photo_path) {
            $this->fileUploadService->deleteFile($user->profile_photo_path);
            $user->update(['profile_photo_path' => null]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile photo removed.',
            'data' => $user,
        ]);
    }
}
