<?php
// ============================================
// Abstract Class: Tiket
// Kelas induk (parent) untuk semua jenis tiket
// ============================================

require_once __DIR__ . '/../koneksi/database.php';

abstract class Tiket
{
    // ========================================
    // Properti Terenkapsulasi (protected)
    // Dipetakan dari kolom tabel_tiket di DB
    // ========================================
    protected ?int    $id_tiket;          // → kolom: id_tiket (PK, AUTO_INCREMENT)
    protected string  $nama_film;         // → kolom: nama_film
    protected string  $jadwal_tayang;     // → kolom: jadwal_tayang
    protected int     $jumlah_kursi;      // → kolom: jumlah_kursi
    protected float   $hargaDasarTiket;   // → kolom: harga_dasar_tiket

    /**
     * Constructor - inisialisasi properti tiket.
     *
     * @param string $nama_film       Nama film yang ditayangkan
     * @param string $jadwal_tayang   Jadwal tayang film (format datetime)
     * @param int    $jumlah_kursi    Jumlah kursi yang dipesan
     * @param float  $hargaDasarTiket Harga dasar tiket
     * @param int|null $id_tiket      ID tiket (null jika baru)
     */
    public function __construct(
        string $nama_film,
        string $jadwal_tayang,
        int    $jumlah_kursi,
        float  $hargaDasarTiket,
        ?int   $id_tiket = null
    ) {
        $this->id_tiket        = $id_tiket;
        $this->nama_film       = $nama_film;
        $this->jadwal_tayang   = $jadwal_tayang;
        $this->jumlah_kursi    = $jumlah_kursi;
        $this->hargaDasarTiket = $hargaDasarTiket;
    }

    // ========================================
    // Metode Abstrak (tanpa isi/body)
    // Wajib diimplementasikan oleh kelas anak
    // ========================================

    /**
     * Menghitung total harga tiket.
     * Setiap jenis tiket (Reguler, IMAX, Velvet) memiliki
     * perhitungan harga yang berbeda-beda.
     *
     * @return float Total harga tiket
     */
    abstract public function hitungTotalHarga(): float;

    /**
     * Menampilkan informasi fasilitas tiket.
     * Setiap jenis tiket memiliki fasilitas yang berbeda
     * sesuai dengan tipe audionya (Reguler, IMAX, Velvet).
     *
     * @return string Informasi fasilitas tiket
     */
    abstract public function tampilkanInformasiFasilitas(): string;

    // ========================================
    // Getter Methods
    // ========================================

    public function getIdTiket(): ?int
    {
        return $this->id_tiket;
    }

    public function getNamaFilm(): string
    {
        return $this->nama_film;
    }

    public function getJadwalTayang(): string
    {
        return $this->jadwal_tayang;
    }

    public function getJumlahKursi(): int
    {
        return $this->jumlah_kursi;
    }

    public function getHargaDasarTiket(): float
    {
        return $this->hargaDasarTiket;
    }

    // ========================================
    // Setter Methods
    // ========================================

    public function setIdTiket(?int $id_tiket): void
    {
        $this->id_tiket = $id_tiket;
    }

    public function setNamaFilm(string $nama_film): void
    {
        $this->nama_film = $nama_film;
    }

    public function setJadwalTayang(string $jadwal_tayang): void
    {
        $this->jadwal_tayang = $jadwal_tayang;
    }

    public function setJumlahKursi(int $jumlah_kursi): void
    {
        $this->jumlah_kursi = $jumlah_kursi;
    }

    public function setHargaDasarTiket(float $hargaDasarTiket): void
    {
        $this->hargaDasarTiket = $hargaDasarTiket;
    }
}
