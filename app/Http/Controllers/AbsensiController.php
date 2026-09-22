<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AbsensiController extends Controller
{
    // ==========================================
    // URL GOOGLE APPS SCRIPT
    // ==========================================

    private function scriptUrl()
    {
        return env('GOOGLE_SCRIPT_URL');
    }


    // ==========================================
    // HALAMAN SCAN QR
    // ==========================================

    public function index()
    {
        return view('scan');
    }


    // ==========================================
    // HALAMAN ABSENSI MANUAL
    // ==========================================

    public function halamanAbsensi()
    {
        return view('absensi');
    }


    // ==========================================
    // MENCARI MAHASISWA BERDASARKAN NIM
    // ==========================================

    public function cariMahasiswa(Request $request)
    {
        $request->validate([
            'nim' => 'required|string'
        ]);

        try {
            $response = Http::get($this->scriptUrl(), [
                'nim' => $request->nim
            ]);

            return response()->json($response->json());

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Gagal menghubungi Google Sheets'
            ], 500);
        }
    }


    // ==========================================
    // MENYIMPAN ABSENSI MAHASISWA
    // ==========================================

    public function simpanAbsensi(Request $request)
    {
        $request->validate([
            'nim' => 'required|string'
        ]);

        try {
            $response = Http::post($this->scriptUrl(), [
                'nim' => $request->nim
            ]);

            return response()->json($response->json());

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Gagal menyimpan absensi'
            ], 500);
        }
    }


    // ==========================================
    // MENAMPILKAN DAFTAR MAHASISWA
    // ==========================================

    public function daftarMahasiswa()
    {
        try {
            $response = Http::get($this->scriptUrl(), [
                'action' => 'list'
            ]);

            $hasil = $response->json();

            $mahasiswa = $hasil['data'] ?? [];

            return view('mahasiswa', compact('mahasiswa'));

        } catch (\Exception $e) {
            return view('mahasiswa', [
                'mahasiswa' => [],
                'error' => 'Gagal mengambil data dari Google Sheets.'
            ]);
        }
    }


    // ==========================================
    // MENAMPILKAN DATA KEHADIRAN
    // ==========================================

    public function dataKehadiran()
    {
        try {
            $response = Http::get($this->scriptUrl(), [
                'action' => 'kehadiran'
            ]);

            $hasil = $response->json();

            $kehadiran = $hasil['data'] ?? [];

            return view('kehadiran', compact('kehadiran'));

        } catch (\Exception $e) {
            return view('kehadiran', [
                'kehadiran' => [],
                'error' => 'Gagal mengambil data kehadiran dari Google Sheets.'
            ]);
        }
    }


    // ==========================================
    // MENAMPILKAN LAPORAN ABSENSI
    // ==========================================

    public function laporan()
    {
        try {

            // Mengambil data mahasiswa
            $responseMahasiswa = Http::get($this->scriptUrl(), [
                'action' => 'list'
            ]);

            $hasilMahasiswa = $responseMahasiswa->json();

            $mahasiswa = $hasilMahasiswa['data'] ?? [];


            // Mengambil data kehadiran
            $responseKehadiran = Http::get($this->scriptUrl(), [
                'action' => 'kehadiran'
            ]);

            $hasilKehadiran = $responseKehadiran->json();

            $kehadiran = $hasilKehadiran['data'] ?? [];


            // Menampilkan halaman laporan
            return view('laporan', compact('mahasiswa', 'kehadiran'));

        } catch (\Exception $e) {

            return view('laporan', [
                'mahasiswa' => [],
                'kehadiran' => [],
                'error' => 'Gagal mengambil data laporan dari Google Sheets.'
            ]);

        }
    }

}