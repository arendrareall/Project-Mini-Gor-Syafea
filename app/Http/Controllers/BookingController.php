<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Menampilkan halaman utama jadwal dan sewa lapangan
     */
    public function index()
    {
        return view('booking.index');
    }

    /**
     * Menampilkan formulir pemesanan dan checkout pembayaran
     */
    public function create()
    {
        return view('booking.form');
    }

    /**
     * Memproses data pesanan sewa lapangan dari formulir
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'phone_number'   => 'required|string|max:20',
            'address'        => 'required|string',
            'payment_method' => 'required|string',
            'payment_proof'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Simpan file bukti transfer ke storage/app/public/bukti_bayar jika ada
        $filePath = null;
        if ($request->hasFile('payment_proof')) {
            $filePath = $request->file('payment_proof')->store('bukti_bayar', 'public');
        }

        return redirect()->route('booking.index')->with('success', 'Pemesanan lapangan berhasil dikonfirmasi! Silakan cek riwayat pesanan Anda.');
    }
}

