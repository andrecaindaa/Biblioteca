<?php

namespace App\Actions;

use Illuminate\Support\Facades\Auth;

class Logout
{
    public function __invoke()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
