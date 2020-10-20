<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class gitaController extends Controller
{
    public function index() {
        return view('gita.dashboard');
    }

    public function guestNonTsel() {
        return view('gita.guest_non_tsel');
    }

    public function guestTsel() {
        return view('gita.guest_tsel');
    }

    public function guestVip() {
        return view('gita.guest_vip');
    }
}
