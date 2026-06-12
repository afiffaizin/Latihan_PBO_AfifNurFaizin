<?php
// ============================================
// Class: TiketImax (extends Tiket)
// Kelas anak untuk tiket studio IMAX
// ============================================

require_once __DIR__ . '/Tiket.php';

class TiketImax extends Tiket
{
    // ========================================
    // Properti Tambahan (private)
    // Dipetakan dari kolom nullable tabel_tiket
    // ========================================
    private ?int    $kecamata3dId;    // → kolom: kecamata_3d_id
    private ?string $efekGerakFitur;  // → kolom: efek_gerak_fitur_bantal_selimut_pack

    /**
     * Constructor - inisialisasi properti TiketImax.
     *
     * @param string      $nama_film       Nama film
     * @param string      $jadwal_tayang   Jadwal tayang
     * @param int         $jumlah_kursi    Jumlah kursi
     * @param float       $hargaDasarTiket Harga dasar tiket
     * @param int|null    $kecamata3dId    ID kacamata 3D (nullable)
     * @param string|null $efekGerakFitur  Efek gerak & fitur (nullable)
     * @param int|null    $id_tiket        ID tiket
     */
    public function __construct(
        string  $nama_film,
        string  $jadwal_tayang,
        int     $jumlah_kursi,
        float   $hargaDasarTiket,
        ?int    $kecamata3dId = null,
        ?string $efekGerakFitur = null,
        ?int    $id_tiket = null
    ) {
        parent::__construct($nama_film, $jadwal_tayang, $jumlah_kursi, $hargaDasarTiket, $id_tiket);
        $this->kecamata3dId   = $kecamata3dId;
        $this->efekGerakFitur = $efekGerakFitur;
    }

    // ========================================
    // Implementasi Metode Abstrak
    // ========================================

    /**
     * Menghitung total harga tiket IMAX.
     * Total Harga = (jumlah_kursi * hargaDasarTiket) + 35000
     * Dikenakan biaya tambahan teknologi proyeksi layar lebar
     * IMAX dan audio flat sebesar Rp35.000.
     *
     * @return float Total harga tiket
     */
    public function hitungTotalHarga(): float
    {
        $biayaTambahanImax = 35000; // biaya flat teknologi IMAX

        return ($this->jumlah_kursi * $this->hargaDasarTiket) + $biayaTambahanImax;
    }

    /**
     * Menampilkan informasi fasilitas tiket IMAX.
     *
     * @return string Informasi fasilitas
     */
    public function tampilkanInformasiFasilitas(): string
    {
        $info  = "=== Fasilitas Tiket IMAX ===\n";
        $info .= "Film            : {$this->nama_film}\n";
        $info .= "Jadwal Tayang   : {$this->jadwal_tayang}\n";
        $info .= "Jumlah Kursi    : {$this->jumlah_kursi}\n";
        $info .= "Harga Dasar     : Rp " . number_format($this->hargaDasarTiket, 2, ',', '.') . "\n";
        $info .= "Biaya IMAX      : + Rp 35.000 (flat)\n";
        $info .= "Total Harga     : Rp " . number_format($this->hitungTotalHarga(), 2, ',', '.') . "\n";
        $info .= "Kacamata 3D ID  : " . ($this->kecamata3dId ?? 'Tidak tersedia') . "\n";
        $info .= "Efek Gerak/Fitur: " . ($this->efekGerakFitur ?? 'Tidak tersedia') . "\n";
        $info .= "============================\n";

        return $info;
    }

    // ========================================
    // Getter & Setter
    // ========================================

    public function getKecamata3dId(): ?int
    {
        return $this->kecamata3dId;
    }

    public function setKecamata3dId(?int $kecamata3dId): void
    {
        $this->kecamata3dId = $kecamata3dId;
    }

    public function getEfekGerakFitur(): ?string
    {
        return $this->efekGerakFitur;
    }

    public function setEfekGerakFitur(?string $efekGerakFitur): void
    {
        $this->efekGerakFitur = $efekGerakFitur;
    }
}
