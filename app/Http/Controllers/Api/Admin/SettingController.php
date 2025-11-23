<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group_name')
            ->orderBy('key')
            ->get();

        $grouped_settings = $settings->groupBy('group_name');

        return response()->json([
            'success' => true,
            'data' => SettingResource::collection($settings),
            'grouped' => $grouped_settings
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string|exists:settings,key',
            'settings.*.value' => 'required',
        ]);

        foreach ($request->settings as $settingData) {
            Setting::where('key', $settingData['key'])
                ->update(['value' => $settingData['value']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully'
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }

    public function changeEmail(ChangeEmailRequest $request)
    {
        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        $user->email = $request->new_email;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Email changed successfully',
            'data' => [
                'email' => $user->email
            ]
        ]);
    }
}
