<?php

namespace App\Http\Controllers;

use App\Enums\UserLocale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function update(Request $request, string $locale): RedirectResponse
    {
        $locale = UserLocale::tryFrom($locale)?->value ?? config('app.locale');

        session(['locale' => $locale]);

        if ($user = $request->user()) {
            $user->update(['locale' => $locale]);
        }

        return back();
    }
}
