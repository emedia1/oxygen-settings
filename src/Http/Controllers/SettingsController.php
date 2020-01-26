<?php

namespace EMedia\Settings\Http\Controllers;

use Storage;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use EMedia\Settings\Entities\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Show main settings page
     */
    public function index()
    {
        $pageTitle = 'Settings';
        $settings = Setting::all();
        $disk = Storage::disk('public');

        return view('settings::settings.index', compact('settings', 'pageTitle'));
    }

    /**
     * Show form to update general app settings
     */
    public function edit(Request $request, Setting $setting)
    {
        return view('settings::settings.edit')->with(['setting' => $setting]);
    }

    /**
     * Update general app settings
     */
    public function update(Request $request, Setting $setting)
    {   $rules = ['required'];

        switch ($setting->input_type) {
            case 'file':
            case 'image':
            case 'pdf':
            case 'audio':
            case 'video':
                $rules[] = 'file';
                break;

            default:
                $rules[] = 'string';
                break;
        }

        $this->validate($request, ['value' => $rules]);

        if ($request->hasFile('value')) {
            $file = $request->file('value');
            $disk = Storage::disk('public');
            $path = $file->store('files', 'public');
            $setting->update([
                'setting_value' => $path,
            ]);
        } elseif ($request->has('value')) {
            $setting->update([
                'setting_value' => $request->input('value'),
            ]);
        }

        return redirect(route('settings.index'))->with('success', 'Setting updated.');
    }

    /**
     * Show form to upload a file for about us
     */
    public function aboutUs()
    {
        $disk = Storage::disk('public');
        $path = 'files/about-us.pdf';
        $url = $disk->exists($path) ? $disk->url($path) : null;
        return view('settings.about-us', compact('url'));
    }

    /**
     * Show form to upload a file for privacy policy
     */
    public function privacyPolicy()
    {
        $disk = Storage::disk('public');
        $path = 'files/privacy-policy.pdf';
        $url = $disk->exists($path) ? $disk->url($path) : null;
        return view('settings.privacy-policy', compact('url'));
    }

    /**
     * Show form to upload a file for terms & conditions
     */
    public function termsConditions()
    {
        $disk = Storage::disk('public');
        $path = 'files/terms-conditions.pdf';
        $url = $disk->exists($path) ? $disk->url($path) : null;
        return view('settings.terms-conditions', compact('url'));
    }

    /**
     * Store the uploaded file
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $type
     * @return \Illuminate\Http\Response
     */
    public function storeFile(Request $request, $type)
    {
        $request->merge(['type' => $type]);
        $this->validate($request, [
            'file' => 'required|mimes:pdf|max:2048',
            'type' => 'required|in:about-us,privacy-policy,terms-conditions',
        ]);

        $disk = Storage::disk('public');
        $path = $request->file->storeAs('files', $type . '.pdf', 'public');
        $url = $disk->url($path);

        if ($path) {
            return redirect('/settings/' . $type)->with('success', 'File uploaded successfully.');
        }

        return redirect('/settings/' . $type)->with('error', 'Error saving file.');
    }
}
