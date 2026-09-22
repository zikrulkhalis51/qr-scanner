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
            $url = $this->scriptUrl();

            // Periksa apakah URL Google Apps Script tersedia
            if (empty($url)) {
                return response()->json([
                    'status' => 'error',
                    'pesan' => 'GOOGLE_SCRIPT_URL belum diatur di Railway.'
                ], 500);
            }

            // Menghubungi Google Apps Script
            $response = Http::timeout(30)->get($url, [
                'nim' => $request->nim
            ]);

            // Periksa respons dari Google Apps Script
            if (!$response->successful()) {
                \Log::error('Gagal mencari mahasiswa dari Google Apps Script', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return response()->json([
                    'status' => 'error',
                    'pesan' => 'Google Apps Script mengembalikan HTTP ' . $response->status()
                ], 502);
            }

            // Ubah respons menjadi JSON
            $data = $response->json();

            // Periksa apakah respons berupa JSON yang valid
            if (!is_array($data)) {
                \Log::error('Respons pencarian Google Apps Script tidak valid', [
                    'body' => $response->body()
                ]);

                return response()->json([
                    'status' => 'error',
                    'pesan' => 'Respons Google Apps Script tidak valid.'
                ], 502);
            }

            return response()->json($data);

        } catch (\Throwable $e) {
            \Log::error('Kesalahan pencarian mahasiswa: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'pesan' => 'Laravel gagal menghubungi Google Apps Script.'
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
            $url = $this->scriptUrl();

            // Periksa apakah URL Google Apps Script tersedia
            if (empty($url)) {
                return response()->json([
                    'status' => 'error',
                    'pesan' => 'GOOGLE_SCRIPT_URL belum diatur di Railway.'
                ], 500);
            }

            // Mengirim data absensi ke Google Apps Script
            $response = Http::timeout(30)->post($url, [
                'nim' => $request->nim
            ]);

            // Periksa respons dari Google Apps Script
            if (!$response->successful()) {
                \Log::error('Gagal menyimpan absensi ke Google Apps Script', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return response()->json([
                    'status' => 'error',
                    'pesan' => 'Google Apps Script mengembalikan HTTP ' . $response->status()
                ], 502);
            }

            // Ubah respons menjadi JSON
            $data = $response->json();

            // Periksa apakah respons berupa JSON yang valid
            if (!is_array($data)) {
                \Log::error('Respons penyimpanan Google Apps Script tidak valid', [
                    'body' => $response->body()
                ]);

                return response()->json([
                    'status' => 'error',
                    'pesan' => 'Respons Google Apps Script tidak valid.'
                ], 502);
            }

            return response()->json($data);

        } catch (\Throwable $e) {
            \Log::error('Kesalahan penyimpanan absensi: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'pesan' => 'Laravel gagal menghubungi Google Apps Script.'
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