<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;

class LogSuccessfulLogout
{
    public function __construct()
    {
        //
    }

    public function handle(Logout $event)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user) {
            $user->is_logged_in = false; // Set is_logged_in menjadi false
            $user->save(); // Simpan perubahan
        }
    }
}
