<?php

namespace App\Http\Controllers;

use App\Models\AppInformationSetting;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class AppInformationController extends Controller
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
            'data' => AppInformationSetting::current(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
            'icon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
        ]);

        $setting = AppInformationSetting::current();
        $updates = ['app_name' => $validated['app_name']];

        if ($request->hasFile('logo')) {
            $oldPath = $setting->logo_path;
            $urls = $this->fileUploadService->uploadFile([$request->file('logo')], 'uploads/app-information');
            if ($url = $urls[0] ?? null) {
                $updates['logo_path'] = $url;
                if ($oldPath) {
                    $this->fileUploadService->deleteFile($oldPath);
                }
            }
        }

        if ($request->hasFile('icon')) {
            $oldPath = $setting->icon_path;
            $urls = $this->fileUploadService->uploadFile([$request->file('icon')], 'uploads/app-information');
            if ($url = $urls[0] ?? null) {
                $updates['icon_path'] = $url;
                if ($oldPath) {
                    $this->fileUploadService->deleteFile($oldPath);
                }
            }
        }

        $setting->update($updates);

        return response()->json([
            'success' => true,
            'message' => 'Application information updated.',
            'data' => $setting,
        ]);
    }

    public function removeLogo()
    {
        $setting = AppInformationSetting::current();

        if ($setting->logo_path) {
            $this->fileUploadService->deleteFile($setting->logo_path);
            $setting->update(['logo_path' => null]);
        }

        return response()->json(['success' => true, 'message' => 'Logo removed.', 'data' => $setting]);
    }

    public function removeIcon()
    {
        $setting = AppInformationSetting::current();

        if ($setting->icon_path) {
            $this->fileUploadService->deleteFile($setting->icon_path);
            $setting->update(['icon_path' => null]);
        }

        return response()->json(['success' => true, 'message' => 'Icon removed.', 'data' => $setting]);
    }
}
