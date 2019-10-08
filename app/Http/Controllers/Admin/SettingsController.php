<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settingsAry = Setting::all();
        $settings = [];
        foreach ($settingsAry as $setting) {
            $settings[$setting->option] = $setting;
        }
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        //Setting::where('option', 'company_eth_wallet')
        //    ->update(['val' => $request->get('company_eth_wallet')]);

        Setting::where('option', 'felta_ratio')
            ->update(['val' => $request->get('felta_ratio')]);

        Setting::where('option', 'withdrawal_fee')
            ->update(['val' => $request->get('withdrawal_fee')]);
        return redirect(route('admin.settings'))->with('success', 'Settings has been updated');
    }
}
