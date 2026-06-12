<?php
// ============================================
// Koneksi Database
// Database: db_latihan_pbo_trpl1a_afifnurfaizin
// ============================================

class Database
{
    private string $host     = "localhost";
    private string $username = "root";
    private string $password = "";
    private string $database = "db_latihan_pbo_trpl1a_afifnurfaizin";

    private ?mysqli $koneksi = null;

    /**
     * Membuat koneksi ke database MySQL.
     * @return mysqli object koneksi
     */
    public function getKoneksi(): mysqli
    {
        if ($this->koneksi === null) {
            $this->koneksi = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->database
            );

            if ($this->koneksi->connect_error) {
                die("Koneksi database gagal: " . $this->koneksi->connect_error);
            }

            $this->koneksi->set_charset("utf8mb4");
        }

        return $this->koneksi;
    }

    /**
     * Menutup koneksi database.
     */
    public function tutupKoneksi(): void
    {
        if ($this->koneksi !== null) {
            $this->koneksi->close();
            $this->koneksi = null;
        }
    }
}
