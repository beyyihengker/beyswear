<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreferensiController extends Controller
{
    public function save(Request $request)
    {
        // Mengambil nilai tema dari request
        // Contoh value: light, dark, atau system
        $theme = $request->theme;

        // Mengambil nilai ukuran font dari request
        // Contoh value: small, medium, atau large
        $fontSize = $request->font_size;

        // Mengembalikan response dalam bentuk JSON
        // sekaligus menyimpan preferensi ke cookie
        return response()->json([

            // Penanda bahwa proses penyimpanan berhasil
            'success' => true,

            // Pesan yang bisa ditampilkan di frontend
            'message' => 'Preferensi berhasil disimpan.',

            // Mengembalikan theme yang disimpan
            'theme' => $theme,

            // Mengembalikan ukuran font yang disimpan
            'font_size' => $fontSize

        ])

        // Menyimpan theme ke cookie selama 7 hari
        ->cookie(
            'theme',
            $theme,
            60 * 24 * 7
        )

        // Menyimpan ukuran font ke cookie selama 7 hari
        ->cookie(
            'font_size',
            $fontSize,
            60 * 24 * 7
        );
    }

    public function getPreference(Request $request)
    {
        // Mengambil data preferensi dari cookie
        // lalu mengembalikannya dalam bentuk JSON
        return response()->json([

            // Mengambil cookie theme
            'theme' => $request->cookie('theme'),

            // Mengambil cookie font_size
            'font_size' => $request->cookie('font_size')
        ]);
    }
}