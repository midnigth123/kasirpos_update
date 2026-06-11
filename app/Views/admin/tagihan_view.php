<?php
/**
 * @var string $pesan
 */
?>

<?= $this->extend('layout/admin_layout') ?>
<?= $this->section('content') ?>

<!DOCTYPE html>
<html lang="id">
<link rel="shortcut icon" type="image/png" href="<?= base_url('icon_kasir.ico') ?>">

<head>
    <meta charset="UTF-8">
    <title>Masa Langganan Habis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.css">
</head>

<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="container text-center">
        <div class="card shadow border-0 mx-auto p-5" style="max-width: 550px; border-radius: 15px;">
            <div class="text-danger mb-4">
                <i class="fas fa-wallet fa-5x animate__animated animate__bounceIn"></i>
            </div>
            <h3 class="fw-bold text-dark mb-2">Layanan Ditangguhkan Sementara</h3>

            <p class="text-muted mb-1">Masa aktif langganan toko Anda telah berakhir.</p>
            <p class="text-danger fw-bold mb-4" style="font-size: 1.1rem;">
                <i class="fas fa-calendar-alt me-1"></i> Tanggal Jatuh Tempo:
                <?= date('d-m-Y', strtotime($tgl_selesai ?? '09-06-2026')) ?>
            </p>

            <div class="alert alert-warning text-start" role="alert">
                <i class="fas fa-info-circle me-2"></i> Untuk melanjutkan penggunaan aplikasi
                <strong>KasirKita</strong>, silakan lakukan perpanjangan paket atau hubungi Admin Penjualan.
            </div>

            <div class="d-grid gap-2 mt-4">
                <?php 
                    $namaToko = session()->get('nama_toko') ?? 'Toko Klien';
                    
                    $pesanWa = "Halo Admin KasirKita, saya mau perpanjang langganan untuk:\n\n"
                            . "• Nama Outlet : " . $namaToko . "\n\n"
                            . "Mohon info prosedur pembayarannya. Terima kasih.";
                            
                    $urlWa = "https://wa.me/628126639311?text=" . urlencode($pesanWa);
                    ?>

                <a href="<?= $urlWa ?>" target="_blank" class="btn btn-success btn-lg">
                    <i class="fab fa-whatsapp me-2"></i>Hubungi Admin (WhatsApp)
                </a>

                <a href="<?= site_url('logout') ?>" class="btn btn-light btn-sm text-muted mt-2">
                    <i class="fas fa-sign-out-alt me-1"></i> Keluar ke Halaman Login
                </a>
            </div>
        </div>
    </div>
</body>

</html>