<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __invoke() {
        // Simpan menu yang aktif di session
        session()->put('active_menu', 'dashboard');

        // Tampilkan view
        return view('dashboard');
    }


}
