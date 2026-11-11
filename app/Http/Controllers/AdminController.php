<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Menampilkan dashboard admin verifikasi pesanan
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Verifikasi atau tolak pesanan sewa lapangan
     */
    public function verify(Request $request, $id)
    {
        $status = $request->input('status', 'Terverifikasi');
        
        // Logika update status ke database
        
        return back()->with('success', "Pesanan {$id} berhasil diubah statusnya menjadi {$status}.");
    }
}

