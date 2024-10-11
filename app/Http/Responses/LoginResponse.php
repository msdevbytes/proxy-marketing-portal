<?php

namespace App\Http\Responses;

use Auth;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('filament.admin.home');
        } else {
            return redirect()->intended(route('filament.admin.pages.admin-dashboard'));
        }
    }
}
