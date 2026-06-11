<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (session()->get('logged_in')) {
            $role = session()->get('role');
            
            if ($role === 'kasir') {
                return redirect()->to(site_url('kasir/transaksi'));
            }
            
            // Peran owner dan manajer diarahkan ke panel dashboard admin utama toko
            return redirect()->to(site_url('admin/dashboard'));
        }
        return view('auth/login');
    }

    public function proses_login()
    {
        $kode_toko = trim((string)$this->request->getPost('kode_toko'));
        $username  = trim((string)$this->request->getPost('username'));
        $password  = $this->request->getPost('password');

        $toko = $this->db->table('master_toko')
            ->where('kode_toko', $kode_toko)
            ->where('status_aktif', 'Y')
            ->get()->getRow();

        if (!$toko) {
            return redirect()->back()->with('error', 'Kode Toko tidak valid atau nonaktif.');
        }

        try {
            $this->db->setDatabase($toko->nama_database);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyambung ke database toko.');
        }

        // 3. Ambil Data User Berdasarkan Username
        $user = $this->db->table('user')
            ->where('username', $username)
            ->get()->getRow();

        if ($user) {
            if (isset($user->is_active) && $user->is_active == 0) {
                return redirect()->back()->with('error', 'Akun Anda di toko ini dinonaktifkan.');
            }

            if (password_verify($password, $user->password) || $password === $user->password) {

                $session = session();
                $role = trim($user->role);

                if ($role !== 'admin' && $role !== 'owner') {
                    $akses_aktif = $this->db->table('pengaturan_akses')
                        ->where('role', $role)
                        ->where('status', 1)
                        ->get()
                        ->getRow();

                    // Mencegah login jika role baru belum dikonfigurasi sama sekali di db
                    if (!$akses_aktif) {
                        return redirect()->to('login')->with('error', 'Akses modul untuk peran Anda belum diaktifkan.');
                    }
                }
                $session->regenerate();
                
                $sessionData = [
                    'id_user'       => $user->id_user,
                    'username'      => $user->username,
                    'nama_user'     => $user->nama_user,
                    'role'          => $role,
                    'nama_toko'     => $toko->nama_toko,
                    'db_client'     => $toko->nama_database, // Disinkronkan dengan filter multi-db Anda
                    'logged_in'     => true
                ];
                $session->set($sessionData);

                // Panggil fungsi log internal lokal
                $this->tambah_log(
                    $user->username, 
                    $role, 
                    'Login Berhasil ke dalam Sistem Kasir'
                );

                // REDIRECT PASCA LOGIN SESUAI STRUKTUR AKSES ROLE
                if ($role === 'kasir') {
                    return redirect()->to(site_url('kasir/absen')); // Wajib presensi masuk
                }

                return redirect()->to(site_url('admin/dashboard'));
            } else {
                return redirect()->back()->with('error', 'Password salah.');
            }
        } else {
            return redirect()->back()->with('error', 'User tidak ditemukan di toko ini.');
        }
    }

    public function logout()
    {
        $session = session();
        $username = $session->get('username') ?: $session->get('nama_user');
        $role     = $session->get('role');

        // REVISI MUTLAK: Hanya kasir yang diarahkan ke halaman tutup buku shift laci
        if ($role === 'kasir') {
            return redirect()->to(site_url('kasir/close-kasir'));
        }

        // AMANKAN LOG LOGOUT: Catat jejak keluar sistem sebelum session dihancurkan
        if ($username) {
            $this->tambah_log($username, $role, 'User melakukan Logout dari sistem');
        }

        // Untuk Admin, Owner, dan Manajer bisa langsung destroy session bersih
        $session->destroy();
        return redirect()->to(site_url('login'))->with('pesan', 'Berhasil keluar.');
    }

    private function tambah_log($user, $role, $log_aktivitas)
    {
        $db = \Config\Database::connect();
        $dbClient = session()->get('db_client');

        if (!empty($dbClient)) {
            try {
                $db->setDatabase($dbClient);
            } catch (\Exception $e) {
                return false;
            }
        }

        $data = [
            'user'      => $user,
            'role'      => $role,
            'aktivitas' => $log_aktivitas,
            'waktu'     => date('Y-m-d H:i:s')
        ];

        $db->table('log_aktivitas')->insert($data);
    }
}