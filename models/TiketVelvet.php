<?php
// ============================================
// Class: TiketVelvet (extends Tiket)
// Kelas anak untuk tiket studio Velvet (Premium)
// ============================================

require_once __DIR__ . '/Tiket.php';

class TiketVelvet extends Tiket
{
    // ========================================
    // Properti Tambahan (private)
    // Dipetakan dari kolom nullable tabel_tiket
    // ========================================
    private ?string $bantalSelimutPack;  // → kolom: efek_gerak_fitur_bantal_selimut_pack
    private ?string $layananButler;      // → kolom: layanan_butler

    /**
     * Constructor - inisialisasi properti TiketVelvet.
     *
     * @param string      $nama_film         Nama film
     * @param string      $jadwal_tayang     Jadwal tayang
     * @param int         $jumlah_kursi      Jumlah kursi
     * @param float       $hargaDasarTiket   Harga dasar tiket
     * @param string|null $bantalSelimutPack Paket bantal & selimut (nullable)
     * @param string|null $layananButler     Layanan butler (nullable)
     * @param int|null    $id_tiket          ID tiket
     */
    public function __construct(
        string  $nama_film,
        string  $jadwal_tayang,
        int     $jumlah_kursi,
        float   $hargaDasarTiket,
        ?string $bantalSelimutPack = null,
        ?string $layananButler = null,
        ?int    $id_tiket = null
    ) {
        parent::__construct($nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket, $id_tiket);
        $this->bantalSelimutPack = $bantalSelimutPack;
        $this->layananButler     = $layananButler;
    }

    // ========================================
    // Implementasi Metode Abstrak
    // ========================================

    /**
     * Menghitung total harga tiket Velvet.
     * Total Harga = (jumlah_kursi * hargaDasarTiket) * 1.50
     * Dikenakan surcharge/biaya tambahan kelas premium
     * sebesar 50% dari total harga dasar.
     *
     * @return float Total harga tiket
     */
    public function hitungTotalHarga(): float
    {
        return ($this->jumlah_kursi * $this->hargaDasarTiket) * 1.50;
    }

    /**
     * Menampilkan informasi fasilitas tiket Velvet.
     *
     * @return string Informasi fasilitas
     */
    public function tampilkanInformasiFasilitas(): string
    {
        $info  = "=== Fasilitas Tiket Velvet (Premium) ===\n";
        $info .= "Film               : {$this->nama_film}\n";
        $info .= "Jadwal Tayang      : {$this->jadwal_tayang}\n";
        $info .= "Jumlah Kursi       : {$this->jumlah_kursi}\n";
        $info .= "Harga Dasar        : Rp " . number_format($this->hargaDasarTiket, 2, ',', '.') . "\n";
        $info .= "Surcharge Velvet   : 100%\n";
        $info .= "Total Harga        : Rp " . number_format($this->hitungTotalHarga(), 2, ',', '.') . "\n";
        $info .= "Bantal & Selimut   : " . ($this->bantalSelimutPack ?? 'Tidak tersedia') . "\n";
        $info .= "Layanan Butler     : " . ($this->layananButler ?? 'Tidak tersedia') . "\n";
        $info .= "========================================\n";

        return $info;
    }

    // ========================================
    // Getter & Setter
    // ========================================

    public function getBantalSelimutPack(): ?string
    {
        return $this->bantalSelimutPack;
    }

    public function setBantalSelimutPack(?string $bantalSelimutPack): void
    {
        $this->bantalSelimutPack = $bantalSelimutPack;
    }

    public function getLayananButler(): ?string
    {
        return $this->layananButler;
    }

    public function setLayananButler(?string $layananButler): void
    {
        $this->layananButler = $layananButler;
    }
}
