<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $user = $request->user();
        if ($user->hasVerifiedEmail()) {
            $destination = $user->role === 'admin' ? route('admin.dashboard') : route('user.tempat-makan.index');
            return redirect()->intended($destination);
        }
        return view('auth.verify-email');
    }
}
