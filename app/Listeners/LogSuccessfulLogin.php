<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;

class LogSuccessfulLogin
{
    public function __construct()
    {
        //
    }

    public function handle(Login $event)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $user->is_logged_in = true; // Ubah status is_logged_in
            $user->save(); // Simpan perubahan
        }
    }
}
