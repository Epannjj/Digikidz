<?php
include "../db.php";
require('../library/fpdf/fpdf.php'); // Make sure to include FPDF library

session_start();

if (isset($_SESSION['nama'])) {
    $nama = $_SESSION['nama'];

    // Get student data
    $sql = "SELECT * FROM siswa WHERE nama = '$nama'";
    $result = mysqli_query($db, $sql);
    $data = mysqli_fetch_assoc($result);

    // Get progress data
    $sql_progres = "SELECT count(`pertemuan`) as progres FROM hasil_presensi WHERE nama = '$nama'";
    $result_progres = mysqli_query($db, $sql_progres);
    $progres = mysqli_fetch_assoc($result_progres);

    // Calculate progress percentage
    $total_pertemuan = 16;
    $current_pertemuan = $progres['progres'] ?: 0;
    $persen = ($current_pertemuan / $total_pertemuan) * 100;

    // Get attendance details
    $sql_attendance = "SELECT * FROM hasil_presensi WHERE nama = '$nama' ORDER BY pertemuan ASC";
    $result_attendance = mysqli_query($db, $sql_attendance);

    // Create PDF
    class PDF extends FPDF
    {
        function Header()
        {
            $this->SetFont('Arial', 'B', 16);
            $this->Cell(0, 10, 'Progress Report', 0, 1, 'C');
            $this->Ln(5);
        }

        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();

    // Student Info
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Student Information', 0, 1);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(30, 8, 'Name:', 0);
    $pdf->Cell(0, 8, $nama, 0, 1);

    $pdf->Cell(30, 8, 'Program:', 0);
    $pdf->Cell(0, 8, $data['program'], 0, 1);

    // Progress Info
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Progress Information', 0, 1);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 8, 'Total Sessions:', 0);
    $pdf->Cell(0, 8, $total_pertemuan, 0, 1);

    $pdf->Cell(50, 8, 'Completed Sessions:', 0);
    $pdf->Cell(0, 8, $current_pertemuan, 0, 1);

    $pdf->Cell(50, 8, 'Progress Percentage:', 0);
    $pdf->Cell(0, 8, round($persen, 2) . '%', 0, 1);

    // Draw progress bar
    $pdf->Ln(5);
    $pdf->Cell(0, 10, 'Progress Bar:', 0, 1);

    $barWidth = 180;
    $barHeight = 10;
    $pdf->SetFillColor(200, 200, 200);
    $pdf->Rect(10, $pdf->GetY(), $barWidth, $barHeight, 'F');

    $pdf->SetFillColor(100, 149, 237); // Cornflower blue
    $fillWidth = ($persen / 100) * $barWidth;
    $pdf->Rect(10, $pdf->GetY(), $fillWidth, $barHeight, 'F');

    $pdf->Ln($barHeight + 5);

    // Attendance Details
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Attendance Details', 0, 1);

    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(30, 8, 'Session', 1);
    $pdf->Cell(50, 8, 'Date', 1);
    $pdf->Cell(50, 8, 'Status', 1);
    $pdf->Cell(0, 8, 'Notes', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 11);

    if (mysqli_num_rows($result_attendance) > 0) {
        while ($row = mysqli_fetch_assoc($result_attendance)) {
            $pdf->Cell(30, 8, 'Session ' . $row['pertemuan'], 1);
            $pdf->Cell(50, 8, isset($row['tanggal']) ? $row['tanggal'] : '-', 1);
            $pdf->Cell(50, 8, 'Attended', 1);
            $pdf->Cell(0, 8, isset($row['keterangan']) ? $row['keterangan'] : '-', 1);
            $pdf->Ln();
        }
    } else {
        $pdf->Cell(0, 8, 'No attendance records found', 1, 1, 'C');
    }

    // Add remaining sessions if not all completed
    if ($current_pertemuan < $total_pertemuan) {
        for ($i = $current_pertemuan + 1; $i <= $total_pertemuan; $i++) {
            $pdf->Cell(30, 8, 'Session ' . $i, 1);
            $pdf->Cell(50, 8, '-', 1);
            $pdf->Cell(50, 8, 'Upcoming', 1);
            $pdf->Cell(0, 8, '-', 1);
            $pdf->Ln();
        }
    }

    // Output the PDF
    $pdf->Output('progress_report.pdf', 'D');
} else {
    // Not logged in
    echo "<script>alert('You need to be logged in to access this page.');</script>";
    echo "<script>window.location.href='login.php';</script>";
}
?>