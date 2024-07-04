<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Koneksi database
include('../config/database.php');

use setasign\Fpdi\Fpdi;
use setasign\Fpdf\Fpdf;

// Pastikan parameter id_periksa tersedia dari URL
if (isset($_GET['id_periksa'])) {
    $id_periksa = $_GET['id_periksa'];

    // Query ambil detail periksa pasien
    $sql_periksa = "SELECT periksa.id, periksa.tgl_periksa, periksa.catatan,
                    pasien.nama AS nama_pasien, pasien.alamat AS alamat_pasien, pasien.no_hp AS no_hp_pasien,
                    dokter.nama AS nama_dokter, dokter.alamat AS alamat_dokter, dokter.no_hp AS no_hp_dokter
                    FROM periksa
                    INNER JOIN pasien ON periksa.id_pasien = pasien.id
                    INNER JOIN dokter ON periksa.id_dokter = dokter.id
                    WHERE periksa.id = ?";
    
    $stmt_periksa = $conn->prepare($sql_periksa);
    $stmt_periksa->bind_param("i", $id_periksa);
    $stmt_periksa->execute();
    $result_periksa = $stmt_periksa->get_result();

    if ($result_periksa->num_rows > 0) {
        $row = $result_periksa->fetch_assoc();

        // Hitung total invoice
        $jasa_dokter = 150000; // Biaya jasa dokter default

        // Query ambil daftar obat yang diresepkan untuk periksa ini
        $sql_obat_periksa = "SELECT obat.nama_obat, obat.harga
                             FROM detail_periksa 
                             INNER JOIN obat ON detail_periksa.id_obat = obat.id
                             WHERE detail_periksa.id_periksa = ?";
        $stmt_obat_periksa = $conn->prepare($sql_obat_periksa);
        $stmt_obat_periksa->bind_param("i", $id_periksa);
        $stmt_obat_periksa->execute();
        $result_obat_periksa = $stmt_obat_periksa->get_result();

        $pdf = new Fpdi();
        $pdf->AddPage();
        
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Nota Periksa', 0, 1, 'C');
        $pdf->Ln(5);
        
        $pdf->Cell(40, 10, 'Nomor Periksa:', 0);
        $pdf->Cell(0, 10, $row['id'], 0, 1);
        $pdf->Cell(40, 10, 'Tanggal Periksa:', 0);
        $pdf->Cell(0, 10, date('d-m-Y', strtotime($row['tgl_periksa'])), 0, 1);
        $pdf->Cell(40, 10, 'Nama Pasien:', 0);
        $pdf->Cell(0, 10, $row['nama_pasien'], 0, 1);
        $pdf->Cell(40, 10, 'Alamat:', 0);
        $pdf->Cell(0, 10, $row['alamat_pasien'], 0, 1);
        $pdf->Cell(40, 10, 'No. HP:', 0);
        $pdf->Cell(0, 10, $row['no_hp_pasien'], 0, 1);
        $pdf->Cell(40, 10, 'Nama Dokter:', 0);
        $pdf->Cell(0, 10, $row['nama_dokter'], 0, 1);
        $pdf->Cell(40, 10, 'Alamat Dokter:', 0);
        $pdf->Cell(0, 10, $row['alamat_dokter'], 0, 1);
        $pdf->Cell(40, 10, 'No. HP Dokter:', 0);
        $pdf->Cell(0, 10, $row['no_hp_dokter'], 0, 1);
        $pdf->Cell(40, 10, 'Jasa Dokter:', 0);
        $pdf->Cell(0, 10, 'Rp ' . number_format($jasa_dokter, 0, ',', '.'), 0, 1);
        $pdf->Ln(5);
        
        $pdf->Cell(40, 10, 'Daftar Obat (Harga):', 0);
        $pdf->Ln(5);
        
        $total_invoice = $jasa_dokter;

        if ($result_obat_periksa->num_rows > 0) {
            while ($row_obat = $result_obat_periksa->fetch_assoc()) {
                $pdf->Cell(40, 10, $row_obat['nama_obat'], 0);
                $pdf->Cell(0, 10, 'Rp ' . number_format($row_obat['harga'], 0, ',', '.'), 0, 1);
                $total_invoice += $row_obat['harga'];
            }
        } else {
            $pdf->Cell(0, 10, '-', 0, 1);
        }
        $pdf->Ln(5);
        
        $pdf->Cell(40, 10, 'Total Invoice:', 0);
        $pdf->Cell(0, 10, 'Rp ' . number_format($total_invoice, 0, ',', '.'), 0, 1);
        
        // Output PDF ke browser
        $pdf->Output('D', 'nota_periksa_' . $row['id'] . '.pdf');
        exit;
        
    } else {
        echo "Data periksa tidak ditemukan.";
    }
} else {
    echo "Parameter id_periksa tidak valid.";
}
?>
