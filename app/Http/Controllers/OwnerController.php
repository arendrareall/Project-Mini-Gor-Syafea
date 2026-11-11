<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OwnerController extends Controller
{
    /**
     * Menampilkan dashboard pemilik, rekap finansial, dan grafik pendapatan
     */
    public function index()
    {
        return view('owner.dashboard');
    }
}

