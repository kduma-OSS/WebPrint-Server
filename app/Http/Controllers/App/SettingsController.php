<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Settings\FortifySettings;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __invoke(Request $request, FortifySettings $fortifySettings, GeneralSettings $generalSettings)
    {
        if (
            ! $request->user()->can('viewAny', FortifySettings::class)
            && ! $request->user()->can('viewAny', GeneralSettings::class)
        ) {
            abort(403);
        }

        return view('app.settings', [
            'fortify_settings' => $fortifySettings,
            'general_settings' => $generalSettings,
        ]);
    }
}
