<?php
/**
 * @var array $rekap
 */
?>

<?= $this->extend('layout/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <div>
            <h4 class="fw-bold mb-0 text-success">Closing Kasir</h4>
            <p class="text-muted mb-0">Rekapitulasi pendapatan harian Senja Coffee</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-dark rounded-pill px-4 shadow-sm">
                <i class="fas fa-print me-2"></i> Cetak Laporan
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4 d-print-none">
        <div class="card-body p-3">
            <form action="" method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="small fw-bold text-muted mb-1 text-uppercase">Pilih Tanggal Closing</label>
                    <input type="date" name="tanggal" class="form-control bg-light border-0"
                        value="<?= isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d') ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100 rounded-3 fw-bold">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="printArea">
        <div class="text-center mb-4 d-none d-print-block">
            <h3 class="fw-bold mb-0 text-uppercase">Senja Coffee & Eatery</h3>
            <p class="mb-1">Laporan Penutupan Kasir (Closing)</p>
            <h6 class="fw-bold">Tanggal:
                <?= isset($_GET['tanggal']) ? date('d M Y', strtotime($_GET['tanggal'])) : date('d M Y') ?></h6>
            <hr>
        </div>

        <div class="row g-3">
            <div class="col-md-4 col-print-12">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-success text-white">
                    <small class="opacity-75 d-block mb-1 text-uppercase fw-bold">Total Pendapatan Bersih</small>
                    <h2 class="fw-bold mb-0">Rp <?= number_format($rekap['total_pendapatan'], 0, ',', '.') ?></h2>
                    <hr class="opacity-25">
                    <small class="small italic opacity-75">Berdasarkan seluruh transaksi 'Lunas'</small>
                </div>
            </div>

            <div class="col-md-8">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="card border-0 shadow-sm rounded-4 p-3 h-100 border-start border-warning border-4">
                            <small class="text-muted d-block mb-1 fw-bold text-uppercase">Tunai (Cash)</small>
                            <h4 class="fw-bold text-dark mb-0">Rp
                                <?= number_format($rekap['total_tunai'] ?? 0, 0, ',', '.') ?></h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm rounded-4 p-3 h-100 border-start border-primary border-4">
                            <small class="text-muted d-block mb-1 fw-bold text-uppercase">Transfer</small>
                            <h4 class="fw-bold text-dark mb-0">Rp
                                <?= number_format($rekap['total_transfer'] ?? 0, 0, ',', '.') ?></h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm rounded-4 p-3 h-100 border-start border-info border-4">
                            <small class="text-muted d-block mb-1 fw-bold text-uppercase">QRIS</small>
                            <h4 class="fw-bold text-dark mb-0">Rp
                                <?= number_format($rekap['total_qris'] ?? 0, 0, ',', '.') ?></h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm rounded-4 p-3 h-100 border-start border-secondary border-4">
                            <small class="text-muted d-block mb-1 fw-bold text-uppercase">EDC</small>
                            <h4 class="fw-bold text-dark mb-0">Rp
                                <?= number_format($rekap['total_edc'] ?? 0, 0, ',', '.') ?></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white border-start border-danger border-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-danger-subtle text-danger p-3 rounded-circle me-3">
                            <i class="fas fa-times-circle fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0 text-danger"><?= $rekap['jumlah_batal'] ?></h4>
                            <small class="text-muted fw-bold">Transaksi Dibatalkan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5 d-none d-print-flex text-center">
            <div class="col-4">
                <p class="mb-5">Kasir On Duty</p>
                <br><br>
                <p class="fw-bold">( ............................ )</p>
            </div>
            <div class="col-4"></div>
            <div class="col-4">
                <p class="mb-5">Manager</p>
                <br><br>
                <p class="fw-bold">( ............................ )</p>
            </div>
        </div>
    </div>
</div>

<style>
/* Perbaikan CSS Print */
@media print {

    .d-print-none,
    .sidebar,
    .navbar,
    .btn,
    form {
        display: none !important;
    }

    .d-print-block {
        display: block !important;
    }

    .d-print-flex {
        display: flex !important;
    }

    body {
        background-color: white !important;
        color: black !important;
    }

    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
        /* Ganti shadow jadi box-shadow agar garis kuning hilang */
        margin-bottom: 10px;
    }

    .bg-success {
        background-color: #198754 !important;
        color: white !important;
        /* Tambahkan versi standar di bawah ini untuk menghilangkan warning */
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }

    .text-success {
        color: #198754 !important;
    }

    .col-print-12 {
        width: 100% !important;
    }
}
</style>

<?= $this->endSection() ?>