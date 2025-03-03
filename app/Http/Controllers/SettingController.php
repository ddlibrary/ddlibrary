<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use BladeView;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return BladeView|Factory|false|Application|View
     */
    public function edit(): View
    {
        $setting =  Setting::first();

        return view('admin.settings.settings_view', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Application|RedirectResponse|Redirector
     *
     * @throws ValidationException
     */
    public function update(Request $request, Setting $setting): RedirectResponse
    {
        $this->validate($request, [
            'website_name' => 'required',
            'website_slogan' => 'required',
            'website_email' => 'required',
        ]);

        $setting->update([
            'website_name' => $request->input('website_name'),
            'website_slogan' => $request->input('website_slogan'),
            'website_email' => $request->input('website_email'),
        ]);

        return redirect('/admin/settings')->with('success', 'Settings updated!');
    }
}
