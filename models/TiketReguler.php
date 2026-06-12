<?php
// ============================================
// Class: TiketReguler (extends Tiket)
// Kelas anak untuk tiket studio Reguler
// ============================================

require_once __DIR__ . '/Tiket.php';

class TiketReguler extends Tiket
{
    // ========================================
    // Properti Tambahan (private)
    // Dipetakan dari kolom nullable tabel_tiket
    // ========================================
    private ?string $tipeAudio;    // → kolom: tipe_audio
    private ?string $lokasiBaris;  // → kolom: lokasi_baris

    /**
     * Constructor - inisialisasi properti TiketReguler.
     *
     * @param string      $nama_film       Nama film
     * @param string      $jadwal_tayang   Jadwal tayang
     * @param int         $jumlah_kursi    Jumlah kursi
     * @param float       $hargaDasarTiket Harga dasar tiket
     * @param string|null $tipeAudio       Tipe audio (nullable)
     * @param string|null $lokasiBaris     Lokasi baris kursi (nullable)
     * @param int|null    $id_tiket        ID tiket
     */
    public function __construct(
        string  $nama_film,
        string  $jadwal_tayang,
        int     $jumlah_kursi,
        float   $hargaDasarTiket,
        ?string $tipeAudio = null,
        ?string $lokasiBaris = null,
        ?int    $id_tiket = null
    ) {
        parent::__construct($nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket, $id_tiket);
        $this->tipeAudio   = $tipeAudio;
        $this->lokasiBaris = $lokasiBaris;
    }

    // ========================================
    // Implementasi Metode Abstrak
    // ========================================

    /**
     * Menghitung total harga tiket Reguler.
     * Tiket Reguler menggunakan harga dasar tanpa biaya tambahan.
     *
     * @return float Total harga tiket
     */
    public function hitungTotalHarga(): float
    {
        return $this->hargaDasarTiket * $this->jumlah_kursi;
    }

    /**
     * Menampilkan informasi fasilitas tiket Reguler.
     *
     * @return string Informasi fasilitas
     */
    public function tampilkanInformasiFasilitas(): string
    {
        $info  = "=== Fasilitas Tiket Reguler ===\n";
        $info .= "Film          : {$this->nama_film}\n";
        $info .= "Jadwal Tayang : {$this->jadwal_tayang}\n";
        $info .= "Jumlah Kursi  : {$this->jumlah_kursi}\n";
        $info .= "Harga Dasar   : Rp " . number_format($this->hargaDasarTiket, 2, ',', '.') . "\n";
        $info .= "Total Harga   : Rp " . number_format($this->hitungTotalHarga(), 2, ',', '.') . "\n";
        $info .= "Tipe Audio    : " . ($this->tipeAudio ?? 'Standar') . "\n";
        $info .= "Lokasi Baris  : " . ($this->lokasiBaris ?? 'Bebas') . "\n";
        $info .= "================================\n";

        return $info;
    }

    // ========================================
    // Getter & Setter
    // ========================================

    public function getTipeAudio(): ?string
    {
        return $this->tipeAudio;
    }

    public function setTipeAudio(?string $tipeAudio): void
    {
        $this->tipeAudio = $tipeAudio;
    }

    public function getLokasiBaris(): ?string
    {
        return $this->lokasiBaris;
    }

    public function setLokasiBaris(?string $lokasiBaris): void
    {
        $this->lokasiBaris = $lokasiBaris;
    }
}
