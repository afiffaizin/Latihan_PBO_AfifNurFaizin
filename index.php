<?php
// ============================================
// View: Dashboard Daftar Tiket Bioskop
// Menampilkan data tiket dari database secara
// dinamis menggunakan polimorfisme OOP
// ============================================

require_once __DIR__ . '/koneksi/database.php';
require_once __DIR__ . '/models/Tiket.php';
require_once __DIR__ . '/models/TiketReguler.php';
require_once __DIR__ . '/models/TiketImax.php';
require_once __DIR__ . '/models/TiketVelvet.php';

// ── Koneksi Database ──
$db      = new Database();
$koneksi = $db->getKoneksi();

// ── Query semua data tiket ──
$query = "SELECT * FROM tabel_tiket ORDER BY jenis_audio ASC, id_tiket ASC";
$hasil = $koneksi->query($query);

// ── Kelompokkan data ke array objek polimorfik ──
$tiketReguler = [];
$tiketImax    = [];
$tiketVelvet  = [];

if ($hasil && $hasil->num_rows > 0) {
    while ($row = $hasil->fetch_assoc()) {
        switch ($row['jenis_audio']) {
            case 'Reguler':
                $tiket = new TiketReguler(
                    $row['nama_film'],
                    $row['jadwal_tayang'],
                    (int) $row['jumlah_kursi'],
                    (float) $row['harga_dasar_tiket'],
                    $row['tipe_audio'],
                    $row['lokasi_baris'],
                    (int) $row['id_tiket']
                );
                $tiketReguler[] = $tiket;
                break;

            case 'IMAX':
                $tiket = new TiketImax(
                    $row['nama_film'],
                    $row['jadwal_tayang'],
                    (int) $row['jumlah_kursi'],
                    (float) $row['harga_dasar_tiket'],
                    $row['kecamata_3d_id'] !== null ? (int) $row['kecamata_3d_id'] : null,
                    $row['efek_gerak_fitur_bantal_selimut_pack'],
                    (int) $row['id_tiket']
                );
                $tiketImax[] = $tiket;
                break;

            case 'Velvet':
                $tiket = new TiketVelvet(
                    $row['nama_film'],
                    $row['jadwal_tayang'],
                    (int) $row['jumlah_kursi'],
                    (float) $row['harga_dasar_tiket'],
                    $row['efek_gerak_fitur_bantal_selimut_pack'],
                    $row['layanan_butler'],
                    (int) $row['id_tiket']
                );
                $tiketVelvet[] = $tiket;
                break;
        }
    }
}

$db->tutupKoneksi();

// ── Helper: Format Rupiah ──
function formatRupiah(float $angka): string
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// ── Helper: Format Tanggal ──
function formatTanggal(string $datetime): string
{
    $dt = new DateTime($datetime);
    return $dt->format('d M Y – H:i');
}

$totalSemuaTiket = count($tiketReguler) + count($tiketImax) + count($tiketVelvet);

// ── Hitung total pendapatan per studio ──
$pendapatanReguler = 0;
foreach ($tiketReguler as $t) {
    $pendapatanReguler += $t->hitungTotalHarga();
}
$pendapatanImax = 0;
foreach ($tiketImax as $t) {
    $pendapatanImax += $t->hitungTotalHarga();
}
$pendapatanVelvet = 0;
foreach ($tiketVelvet as $t) {
    $pendapatanVelvet += $t->hitungTotalHarga();
}
$totalPendapatan = $pendapatanReguler + $pendapatanImax + $pendapatanVelvet;

// ── Hitung total kursi ──
$totalKursi = 0;
foreach ($tiketReguler as $t) {
    $totalKursi += $t->getJumlahKursi();
}
foreach ($tiketImax as $t) {
    $totalKursi += $t->getJumlahKursi();
}
foreach ($tiketVelvet as $t) {
    $totalKursi += $t->getJumlahKursi();
}

// ── Hitung kursi per studio ──
$kursiReguler = 0;
foreach ($tiketReguler as $t) {
    $kursiReguler += $t->getJumlahKursi();
}
$kursiImax = 0;
foreach ($tiketImax as $t) {
    $kursiImax += $t->getJumlahKursi();
}
$kursiVelvet = 0;
foreach ($tiketVelvet as $t) {
    $kursiVelvet += $t->getJumlahKursi();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard Tiket Bioskop - Studio Reguler, IMAX, dan Velvet. Latihan PBO TRPL1A Afif Nur Faizin.">
    <title>CineBoard — Dashboard Tiket Bioskop</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
</head>

<body>

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar__brand">
            <div class="sidebar__logo">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect>
                    <line x1="7" y1="2" x2="7" y2="22"></line>
                    <line x1="17" y1="2" x2="17" y2="22"></line>
                    <line x1="2" y1="12" x2="22" y2="12"></line>
                    <line x1="2" y1="7" x2="7" y2="7"></line>
                    <line x1="2" y1="17" x2="7" y2="17"></line>
                    <line x1="17" y1="7" x2="22" y2="7"></line>
                    <line x1="17" y1="17" x2="22" y2="17"></line>
                </svg>
            </div>
            <div class="sidebar__brand-text">
                <span class="sidebar__brand-name">CineBoard</span>
                <span class="sidebar__brand-sub">Dashboard v1.0</span>
            </div>
        </div>

        <nav class="sidebar__nav">
            <div class="sidebar__nav-label">Menu Utama</div>
            <a href="#dashboard" class="sidebar__link sidebar__link--active" id="nav-dashboard" data-section="dashboard">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                <span>Dashboard</span>
            </a>

            <div class="sidebar__nav-label">Studio</div>
            <a href="#studio-reguler" class="sidebar__link" id="nav-reguler" data-section="studio-reguler">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="23 7 16 12 23 17 23 7"></polygon>
                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                </svg>
                <span>Studio Reguler</span>
                <span class="sidebar__badge sidebar__badge--reguler"><?= count($tiketReguler) ?></span>
            </a>
            <a href="#studio-imax" class="sidebar__link" id="nav-imax" data-section="studio-imax">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
                <span>Studio IMAX</span>
                <span class="sidebar__badge sidebar__badge--imax"><?= count($tiketImax) ?></span>
            </a>
            <a href="#studio-velvet" class="sidebar__link" id="nav-velvet" data-section="studio-velvet">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
                <span>Studio Velvet</span>
                <span class="sidebar__badge sidebar__badge--velvet"><?= count($tiketVelvet) ?></span>
            </a>

            <div class="sidebar__nav-label">Info</div>
            <a href="#polimorfisme" class="sidebar__link" id="nav-polimorfisme" data-section="polimorfisme">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="16 18 22 12 16 6"></polyline>
                    <polyline points="8 6 2 12 8 18"></polyline>
                </svg>
                <span>Polimorfisme OOP</span>
            </a>
        </nav>

        <div class="sidebar__footer">
            <div class="sidebar__db-status">
                <span class="sidebar__db-dot"></span>
                <span>DB Connected</span>
            </div>
            <div class="sidebar__credits">
                Afif Nur Faizin<br>
                <small>TRPL1A — PBO <?= date('Y') ?></small>
            </div>
        </div>
    </aside>

    <!-- ===== MOBILE TOPBAR ===== -->
    <div class="topbar" id="topbar">
        <button class="topbar__toggle" id="sidebar-toggle" aria-label="Toggle sidebar">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <span class="topbar__title">🎬 CineBoard</span>
        <div class="topbar__right">
            <span class="topbar__badge"><?= $totalSemuaTiket ?> Tiket</span>
        </div>
    </div>

    <!-- ===== SIDEBAR OVERLAY (Mobile) ===== -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="main-content" id="main-content">

        <!-- ========================================================== -->
        <!-- SECTION: DASHBOARD                                         -->
        <!-- ========================================================== -->
        <section class="content-section" id="dashboard">
            <div class="section-header">
                <div>
                    <h1 class="section-header__title">Dashboard</h1>
                    <p class="section-header__subtitle">Ringkasan data tiket bioskop dari database</p>
                </div>
                <div class="section-header__db-badge">
                    <span class="dot-live"></span>
                    db_latihan_pbo_trpl1a_afifnurfaizin
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card stat-card--total">
                    <div class="stat-card__icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                    </div>
                    <div class="stat-card__info">
                        <span class="stat-card__value"><?= $totalSemuaTiket ?></span>
                        <span class="stat-card__label">Total Tiket</span>
                    </div>
                </div>
                <div class="stat-card stat-card--revenue">
                    <div class="stat-card__icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23" />
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg>
                    </div>
                    <div class="stat-card__info">
                        <span class="stat-card__value"><?= formatRupiah($totalPendapatan) ?></span>
                        <span class="stat-card__label">Total Pendapatan</span>
                    </div>
                </div>
                <div class="stat-card stat-card--seats">
                    <div class="stat-card__icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </div>
                    <div class="stat-card__info">
                        <span class="stat-card__value"><?= $totalKursi ?></span>
                        <span class="stat-card__label">Total Kursi Terpesan</span>
                    </div>
                </div>
                <div class="stat-card stat-card--studio">
                    <div class="stat-card__icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                    </div>
                    <div class="stat-card__info">
                        <span class="stat-card__value">3</span>
                        <span class="stat-card__label">Kategori Studio</span>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="charts-row">
                <div class="chart-card">
                    <h3 class="chart-card__title">Distribusi Tiket per Studio</h3>
                    <div class="chart-card__body">
                        <canvas id="chartDistribusi"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <h3 class="chart-card__title">Pendapatan per Studio</h3>
                    <div class="chart-card__body">
                        <canvas id="chartPendapatan"></canvas>
                    </div>
                </div>
            </div>

            <!-- Charts Row 2 -->
            <div class="charts-row">
                <div class="chart-card">
                    <h3 class="chart-card__title">Kapasitas Kursi per Studio</h3>
                    <div class="chart-card__body">
                        <canvas id="chartKursi"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <h3 class="chart-card__title">Rata-rata Harga Tiket</h3>
                    <div class="chart-card__body">
                        <canvas id="chartHarga"></canvas>
                    </div>
                </div>
            </div>

            <!-- Studio Quick Cards -->
            <div class="studio-overview-grid">
                <a href="#studio-reguler" class="studio-card studio-card--reguler" data-nav="nav-reguler">
                    <div class="studio-card__header">
                        <div class="studio-card__icon-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                            </svg>
                        </div>
                        <span class="studio-card__badge"><?= count($tiketReguler) ?> tiket</span>
                    </div>
                    <h3 class="studio-card__title">Studio Reguler</h3>
                    <p class="studio-card__desc">Tarif standar — tanpa biaya tambahan</p>
                    <div class="studio-card__footer">
                        <span class="studio-card__stat"><?= formatRupiah($pendapatanReguler) ?></span>
                        <span class="studio-card__action-text">Lihat Detail →</span>
                    </div>
                </a>

                <a href="#studio-imax" class="studio-card studio-card--imax" data-nav="nav-imax">
                    <div class="studio-card__header">
                        <div class="studio-card__icon-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                <line x1="8" y1="21" x2="16" y2="21"></line>
                                <line x1="12" y1="17" x2="12" y2="21"></line>
                            </svg>
                        </div>
                        <span class="studio-card__badge"><?= count($tiketImax) ?> tiket</span>
                    </div>
                    <h3 class="studio-card__title">Studio IMAX</h3>
                    <p class="studio-card__desc">Teknologi layar lebar + Rp 35.000</p>
                    <div class="studio-card__footer">
                        <span class="studio-card__stat"><?= formatRupiah($pendapatanImax) ?></span>
                        <span class="studio-card__action-text">Lihat Detail →</span>
                    </div>
                </a>

                <a href="#studio-velvet" class="studio-card studio-card--velvet" data-nav="nav-velvet">
                    <div class="studio-card__header">
                        <div class="studio-card__icon-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                            </svg>
                        </div>
                        <span class="studio-card__badge"><?= count($tiketVelvet) ?> tiket</span>
                    </div>
                    <h3 class="studio-card__title">Studio Velvet</h3>
                    <p class="studio-card__desc">Premium surcharge 50%</p>
                    <div class="studio-card__footer">
                        <span class="studio-card__stat"><?= formatRupiah($pendapatanVelvet) ?></span>
                        <span class="studio-card__action-text">Lihat Detail →</span>
                    </div>
                </a>
            </div>
        </section>

        <!-- ========================================================== -->
        <!-- SECTION: STUDIO REGULER                                    -->
        <!-- ========================================================== -->
        <section class="content-section" id="studio-reguler">
            <div class="section-header">
                <div>
                    <h2 class="section-header__title section-header__title--reguler">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="23 7 16 12 23 17 23 7"></polygon>
                            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                        </svg>
                        Studio Reguler
                    </h2>
                    <p class="section-header__subtitle">Tarif standar murni — tanpa biaya tambahan fasilitas</p>
                </div>
                <span class="section-header__count section-header__count--reguler"><?= count($tiketReguler) ?> tiket dipesan</span>
            </div>

            <!-- Spesifikasi Fasilitas -->
            <div class="facility-card facility-card--reguler">
                <h4 class="facility-card__title">Spesifikasi Fasilitas Studio</h4>
                <div class="facility-card__grid">
                    <div class="facility-card__item">
                        <span class="facility-card__icon">🔊</span>
                        <span class="facility-card__label">Audio</span>
                        <span class="facility-card__value">Standar Stereo</span>
                    </div>
                    <div class="facility-card__item">
                        <span class="facility-card__icon">💺</span>
                        <span class="facility-card__label">Kursi</span>
                        <span class="facility-card__value">Standar</span>
                    </div>
                    <div class="facility-card__item">
                        <span class="facility-card__icon">📐</span>
                        <span class="facility-card__label">Layar</span>
                        <span class="facility-card__value">Standard Screen</span>
                    </div>
                    <div class="facility-card__item">
                        <span class="facility-card__icon">💰</span>
                        <span class="facility-card__label">Biaya Tambahan</span>
                        <span class="facility-card__value">Tidak Ada</span>
                    </div>
                </div>
            </div>

            <!-- Daftar Tiket (Polimorfisme: tampilkanInformasiFasilitas + hitungTotalHarga) -->
            <?php if (count($tiketReguler) > 0): ?>
                <div class="ticket-grid">
                    <?php /** @var TiketReguler $t */ foreach ($tiketReguler as $t): ?>
                        <div class="ticket-card ticket-card--reguler">
                            <div class="ticket-card__top">
                                <span class="ticket-card__id">#<?= $t->getIdTiket() ?></span>
                                <span class="ticket-card__type">Reguler</span>
                            </div>
                            <h4 class="ticket-card__film"><?= htmlspecialchars($t->getNamaFilm()) ?></h4>
                            <p class="ticket-card__jadwal"><?= formatTanggal($t->getJadwalTayang()) ?></p>
                            <div class="ticket-card__details">
                                <div class="ticket-card__detail">
                                    <span class="ticket-card__detail-label">Kursi</span>
                                    <span class="ticket-card__detail-value"><?= $t->getJumlahKursi() ?></span>
                                </div>
                                <div class="ticket-card__detail">
                                    <span class="ticket-card__detail-label">Harga Dasar</span>
                                    <span class="ticket-card__detail-value"><?= formatRupiah($t->getHargaDasarTiket()) ?></span>
                                </div>
                            </div>
                            <div class="ticket-card__facilities">
                                <!-- Polimorfisme: atribut unik TiketReguler -->
                                <span class="ticket-card__tag">🔊 <?= $t->getTipeAudio() ? htmlspecialchars($t->getTipeAudio()) : 'Audio Standar' ?></span>
                                <span class="ticket-card__tag">💺 <?= $t->getLokasiBaris() ? htmlspecialchars($t->getLokasiBaris()) : 'Bebas' ?></span>
                            </div>
                            <!-- Polimorfisme: tampilkanInformasiFasilitas() -->
                            <div class="ticket-card__poly">
                                <div class="ticket-card__poly-label">Informasi Fasilitas</div>
                                <pre class="ticket-card__poly-output"><?= htmlspecialchars($t->tampilkanInformasiFasilitas()) ?></pre>
                            </div>
                            <div class="ticket-card__total">
                                <span class="ticket-card__total-label">Total Harga</span>
                                <span class="ticket-card__total-value ticket-card__total-value--reguler"><?= formatRupiah($t->hitungTotalHarga()) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state__icon">🎥</div>
                    <p class="empty-state__text">Belum ada tiket Studio Reguler</p>
                </div>
            <?php endif; ?>
        </section>

        <!-- ========================================================== -->
        <!-- SECTION: STUDIO IMAX                                       -->
        <!-- ========================================================== -->
        <section class="content-section" id="studio-imax">
            <div class="section-header">
                <div>
                    <h2 class="section-header__title section-header__title--imax">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                        Studio IMAX
                    </h2>
                    <p class="section-header__subtitle">Biaya tambahan teknologi proyeksi layar lebar IMAX + Rp 35.000</p>
                </div>
                <span class="section-header__count section-header__count--imax"><?= count($tiketImax) ?> tiket dipesan</span>
            </div>

            <!-- Spesifikasi Fasilitas -->
            <div class="facility-card facility-card--imax">
                <h4 class="facility-card__title">Spesifikasi Fasilitas Studio</h4>
                <div class="facility-card__grid">
                    <div class="facility-card__item">
                        <span class="facility-card__icon">🔊</span>
                        <span class="facility-card__label">Audio</span>
                        <span class="facility-card__value">Dolby Atmos / Surround</span>
                    </div>
                    <div class="facility-card__item">
                        <span class="facility-card__icon">🥽</span>
                        <span class="facility-card__label">3D</span>
                        <span class="facility-card__value">Kacamata 3D IMAX</span>
                    </div>
                    <div class="facility-card__item">
                        <span class="facility-card__icon">📐</span>
                        <span class="facility-card__label">Layar</span>
                        <span class="facility-card__value">IMAX Widescreen</span>
                    </div>
                    <div class="facility-card__item">
                        <span class="facility-card__icon">💰</span>
                        <span class="facility-card__label">Biaya Tambahan</span>
                        <span class="facility-card__value">+ Rp 35.000 (flat)</span>
                    </div>
                </div>
            </div>

            <!-- Daftar Tiket -->
            <?php if (count($tiketImax) > 0): ?>
                <div class="ticket-grid">
                    <?php /** @var TiketImax $t */ foreach ($tiketImax as $t): ?>
                        <div class="ticket-card ticket-card--imax">
                            <div class="ticket-card__top">
                                <span class="ticket-card__id">#<?= $t->getIdTiket() ?></span>
                                <span class="ticket-card__type">IMAX</span>
                            </div>
                            <h4 class="ticket-card__film"><?= htmlspecialchars($t->getNamaFilm()) ?></h4>
                            <p class="ticket-card__jadwal"><?= formatTanggal($t->getJadwalTayang()) ?></p>
                            <div class="ticket-card__details">
                                <div class="ticket-card__detail">
                                    <span class="ticket-card__detail-label">Kursi</span>
                                    <span class="ticket-card__detail-value"><?= $t->getJumlahKursi() ?></span>
                                </div>
                                <div class="ticket-card__detail">
                                    <span class="ticket-card__detail-label">Harga Dasar</span>
                                    <span class="ticket-card__detail-value"><?= formatRupiah($t->getHargaDasarTiket()) ?></span>
                                </div>
                            </div>
                            <div class="ticket-card__facilities">
                                <!-- Polimorfisme: atribut unik TiketImax -->
                                <?php if ($t->getKecamata3dId()): ?>
                                    <span class="ticket-card__tag">🥽 3D ID: <?= $t->getKecamata3dId() ?></span>
                                <?php endif; ?>
                                <?php if ($t->getEfekGerakFitur()): ?>
                                    <span class="ticket-card__tag">🎢 <?= htmlspecialchars($t->getEfekGerakFitur()) ?></span>
                                <?php endif; ?>
                            </div>
                            <!-- Polimorfisme: tampilkanInformasiFasilitas() -->
                            <div class="ticket-card__poly">
                                <div class="ticket-card__poly-label">Informasi Fasilitas</div>
                                <pre class="ticket-card__poly-output"><?= htmlspecialchars($t->tampilkanInformasiFasilitas()) ?></pre>
                            </div>
                            <div class="ticket-card__total">
                                <span class="ticket-card__total-label">Total Harga</span>
                                <span class="ticket-card__total-value ticket-card__total-value--imax"><?= formatRupiah($t->hitungTotalHarga()) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state__icon">🖥️</div>
                    <p class="empty-state__text">Belum ada tiket Studio IMAX</p>
                </div>
            <?php endif; ?>
        </section>

        <!-- ========================================================== -->
        <!-- SECTION: STUDIO VELVET                                     -->
        <!-- ========================================================== -->
        <section class="content-section" id="studio-velvet">
            <div class="section-header">
                <div>
                    <h2 class="section-header__title section-header__title--velvet">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                        Studio Velvet
                    </h2>
                    <p class="section-header__subtitle">Surcharge premium 50% — termasuk bantal, selimut & butler service</p>
                </div>
                <span class="section-header__count section-header__count--velvet"><?= count($tiketVelvet) ?> tiket dipesan</span>
            </div>

            <!-- Spesifikasi Fasilitas -->
            <div class="facility-card facility-card--velvet">
                <h4 class="facility-card__title">Spesifikasi Fasilitas Studio</h4>
                <div class="facility-card__grid">
                    <div class="facility-card__item">
                        <span class="facility-card__icon">🔊</span>
                        <span class="facility-card__label">Audio</span>
                        <span class="facility-card__value">Dolby Atmos / Surround</span>
                    </div>
                    <div class="facility-card__item">
                        <span class="facility-card__icon">🛋️</span>
                        <span class="facility-card__label">Comfort</span>
                        <span class="facility-card__value">Bantal & Selimut Pack</span>
                    </div>
                    <div class="facility-card__item">
                        <span class="facility-card__icon">🤵</span>
                        <span class="facility-card__label">Butler</span>
                        <span class="facility-card__value">Layanan Butler Eksklusif</span>
                    </div>
                    <div class="facility-card__item">
                        <span class="facility-card__icon">💰</span>
                        <span class="facility-card__label">Surcharge</span>
                        <span class="facility-card__value">+ 50% Premium</span>
                    </div>
                </div>
            </div>

            <!-- Daftar Tiket -->
            <?php if (count($tiketVelvet) > 0): ?>
                <div class="ticket-grid">
                    <?php /** @var TiketVelvet $t */ foreach ($tiketVelvet as $t): ?>
                        <div class="ticket-card ticket-card--velvet">
                            <div class="ticket-card__top">
                                <span class="ticket-card__id">#<?= $t->getIdTiket() ?></span>
                                <span class="ticket-card__type">Velvet</span>
                            </div>
                            <h4 class="ticket-card__film"><?= htmlspecialchars($t->getNamaFilm()) ?></h4>
                            <p class="ticket-card__jadwal"><?= formatTanggal($t->getJadwalTayang()) ?></p>
                            <div class="ticket-card__details">
                                <div class="ticket-card__detail">
                                    <span class="ticket-card__detail-label">Kursi</span>
                                    <span class="ticket-card__detail-value"><?= $t->getJumlahKursi() ?></span>
                                </div>
                                <div class="ticket-card__detail">
                                    <span class="ticket-card__detail-label">Harga Dasar</span>
                                    <span class="ticket-card__detail-value"><?= formatRupiah($t->getHargaDasarTiket()) ?></span>
                                </div>
                            </div>
                            <div class="ticket-card__facilities">
                                <!-- Polimorfisme: atribut unik TiketVelvet -->
                                <?php if ($t->getBantalSelimutPack()): ?>
                                    <span class="ticket-card__tag">🛋️ <?= htmlspecialchars($t->getBantalSelimutPack()) ?></span>
                                <?php endif; ?>
                                <?php if ($t->getLayananButler()): ?>
                                    <span class="ticket-card__tag">🤵 <?= htmlspecialchars($t->getLayananButler()) ?></span>
                                <?php endif; ?>
                            </div>
                            <!-- Polimorfisme: tampilkanInformasiFasilitas() -->
                            <div class="ticket-card__poly">
                                <div class="ticket-card__poly-label">Informasi Fasilitas</div>
                                <pre class="ticket-card__poly-output"><?= htmlspecialchars($t->tampilkanInformasiFasilitas()) ?></pre>
                            </div>
                            <div class="ticket-card__total">
                                <span class="ticket-card__total-label">Total Harga</span>
                                <span class="ticket-card__total-value ticket-card__total-value--velvet"><?= formatRupiah($t->hitungTotalHarga()) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state__icon">👑</div>
                    <p class="empty-state__text">Belum ada tiket Studio Velvet</p>
                </div>
            <?php endif; ?>
        </section>

        <!-- ========================================================== -->
        <!-- SECTION: POLIMORFISME OOP                                  -->
        <!-- ========================================================== -->
        <section class="content-section" id="polimorfisme">
            <div class="section-header">
                <div>
                    <h2 class="section-header__title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="16 18 22 12 16 6"></polyline>
                            <polyline points="8 6 2 12 8 18"></polyline>
                        </svg>
                        Polimorfisme OOP
                    </h2>
                    <p class="section-header__subtitle">Diagram hierarki kelas dan metode polimorfik yang digunakan</p>
                </div>
            </div>

            <div class="oop-info">
                <div class="oop-card">
                    <div class="oop-card__header">
                        <span class="oop-card__badge oop-card__badge--abstract">abstract</span>
                        <h4>class Tiket</h4>
                    </div>
                    <p class="oop-card__desc">Kelas induk (parent) — mendefinisikan properti umum dan metode abstrak</p>
                    <div class="oop-card__methods">
                        <div class="oop-card__method oop-card__method--abstract">
                            <code>abstract hitungTotalHarga(): float</code>
                            <span>Setiap studio memiliki formula harga berbeda</span>
                        </div>
                        <div class="oop-card__method oop-card__method--abstract">
                            <code>abstract tampilkanInformasiFasilitas(): string</code>
                            <span>Setiap studio menampilkan fasilitas uniknya</span>
                        </div>
                    </div>
                </div>

                <div class="oop-children">
                    <div class="oop-card oop-card--reguler">
                        <div class="oop-card__header">
                            <span class="oop-card__badge oop-card__badge--reguler">extends</span>
                            <h4>TiketReguler</h4>
                        </div>
                        <div class="oop-card__methods">
                            <div class="oop-card__method">
                                <code>hitungTotalHarga()</code>
                                <span>jumlah_kursi × harga_dasar</span>
                            </div>
                            <div class="oop-card__method">
                                <code>tampilkanInformasiFasilitas()</code>
                                <span>Tipe Audio, Lokasi Baris</span>
                            </div>
                        </div>
                    </div>
                    <div class="oop-card oop-card--imax">
                        <div class="oop-card__header">
                            <span class="oop-card__badge oop-card__badge--imax">extends</span>
                            <h4>TiketImax</h4>
                        </div>
                        <div class="oop-card__methods">
                            <div class="oop-card__method">
                                <code>hitungTotalHarga()</code>
                                <span>(kursi × harga) + 35.000</span>
                            </div>
                            <div class="oop-card__method">
                                <code>tampilkanInformasiFasilitas()</code>
                                <span>Kacamata 3D, Efek Gerak</span>
                            </div>
                        </div>
                    </div>
                    <div class="oop-card oop-card--velvet">
                        <div class="oop-card__header">
                            <span class="oop-card__badge oop-card__badge--velvet">extends</span>
                            <h4>TiketVelvet</h4>
                        </div>
                        <div class="oop-card__methods">
                            <div class="oop-card__method">
                                <code>hitungTotalHarga()</code>
                                <span>(kursi × harga) × 1.50</span>
                            </div>
                            <div class="oop-card__method">
                                <code>tampilkanInformasiFasilitas()</code>
                                <span>Bantal Selimut, Butler</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===== FOOTER ===== -->
        <footer class="footer">
            <div class="footer__polimorfisme">
                Polimorfisme OOP — hitungTotalHarga() & tampilkanInformasiFasilitas()
            </div>
            <p>Latihan PBO — TRPL1A — Afif Nur Faizin &copy; <?= date('Y') ?></p>
        </footer>

    </main>

    <script>
        // ===== Sidebar Toggle (Mobile) =====
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar--open');
            sidebarOverlay.classList.toggle('active');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('sidebar--open');
            sidebarOverlay.classList.remove('active');
        });

        // ===== SPA-style Section Show/Hide =====
        const navLinks = document.querySelectorAll('.sidebar__link');
        const sections = document.querySelectorAll('.content-section');

        function showSection(sectionId) {
            sections.forEach(s => s.classList.remove('content-section--active'));
            const target = document.getElementById(sectionId);
            if (target) target.classList.add('content-section--active');

            navLinks.forEach(link => {
                link.classList.remove('sidebar__link--active');
                if (link.dataset.section === sectionId) link.classList.add('sidebar__link--active');
            });

            document.getElementById('main-content').scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            sidebar.classList.remove('sidebar--open');
            sidebarOverlay.classList.remove('active');
        }

        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                showSection(link.dataset.section);
            });
        });

        // Studio overview cards navigation
        document.querySelectorAll('.studio-card').forEach(card => {
            card.addEventListener('click', (e) => {
                e.preventDefault();
                const navId = card.dataset.nav;
                if (navId) {
                    const navEl = document.getElementById(navId);
                    if (navEl) showSection(navEl.dataset.section);
                }
            });
        });

        // ===== Initialize =====
        window.addEventListener('load', () => {
            showSection('dashboard');
            initCharts();
        });

        // ===== Chart.js Charts =====
        function initCharts() {
            const regulerColor = '#3b82f6';
            const imaxColor = '#f59e0b';
            const velvetColor = '#8b5cf6';
            const regulerBg = 'rgba(59, 130, 246, 0.15)';
            const imaxBg = 'rgba(245, 158, 11, 0.15)';
            const velvetBg = 'rgba(139, 92, 246, 0.15)';

            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#64748b';

            // 1. Doughnut — Distribusi Tiket
            new Chart(document.getElementById('chartDistribusi'), {
                type: 'doughnut',
                data: {
                    labels: ['Reguler', 'IMAX', 'Velvet'],
                    datasets: [{
                        data: [<?= count($tiketReguler) ?>, <?= count($tiketImax) ?>, <?= count($tiketVelvet) ?>],
                        backgroundColor: [regulerColor, imaxColor, velvetColor],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    size: 12,
                                    weight: '500'
                                }
                            }
                        }
                    }
                }
            });

            // 2. Bar — Pendapatan per Studio
            new Chart(document.getElementById('chartPendapatan'), {
                type: 'bar',
                data: {
                    labels: ['Reguler', 'IMAX', 'Velvet'],
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: [<?= $pendapatanReguler ?>, <?= $pendapatanImax ?>, <?= $pendapatanVelvet ?>],
                        backgroundColor: [regulerBg, imaxBg, velvetBg],
                        borderColor: [regulerColor, imaxColor, velvetColor],
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#e2e8f0'
                            },
                            ticks: {
                                callback: v => 'Rp ' + (v / 1000000).toFixed(1) + 'jt'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // 3. Bar — Kursi per Studio
            new Chart(document.getElementById('chartKursi'), {
                type: 'bar',
                data: {
                    labels: ['Reguler', 'IMAX', 'Velvet'],
                    datasets: [{
                        label: 'Total Kursi',
                        data: [<?= $kursiReguler ?>, <?= $kursiImax ?>, <?= $kursiVelvet ?>],
                        backgroundColor: [regulerBg, imaxBg, velvetBg],
                        borderColor: [regulerColor, imaxColor, velvetColor],
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#e2e8f0'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // 4. Horizontal Bar — Rata-rata Harga
            const avgReguler = <?= count($tiketReguler) > 0 ? round($pendapatanReguler / count($tiketReguler)) : 0 ?>;
            const avgImax = <?= count($tiketImax) > 0 ? round($pendapatanImax / count($tiketImax)) : 0 ?>;
            const avgVelvet = <?= count($tiketVelvet) > 0 ? round($pendapatanVelvet / count($tiketVelvet)) : 0 ?>;

            new Chart(document.getElementById('chartHarga'), {
                type: 'bar',
                data: {
                    labels: ['Reguler', 'IMAX', 'Velvet'],
                    datasets: [{
                        label: 'Rata-rata (Rp)',
                        data: [avgReguler, avgImax, avgVelvet],
                        backgroundColor: [regulerBg, imaxBg, velvetBg],
                        borderColor: [regulerColor, imaxColor, velvetColor],
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: '#e2e8f0'
                            },
                            ticks: {
                                callback: v => 'Rp ' + (v / 1000000).toFixed(1) + 'jt'
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    </script>

</body>

</html>