<?php

namespace App\Http\Controllers;

use App\Models\adminSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = adminSetting::get();
        return view('setting.index', compact('settings'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'value' => 'required|string',
            'setting_id' => 'required|exists:admin_settings,id',
        ]);
        $setting = adminSetting::find($request->setting_id);
        if (!$setting) {
            return redirect()->back()->withErrors(['error' => 'Setting not found']);
        }
        $setting->value = $request->value;
        $setting->save();
        return redirect()->route('settings.index')->with('success', 'Settings updated successfully');
    }
}
