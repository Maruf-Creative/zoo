<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'vendor/tcpdf/tcpdf.php';

class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 16);
        $this->SetTextColor(40, 167, 69); // warna hijau bootstrap
        $this->Cell(0, 15, 'ZOO ADMIN - DATA HEWAN KEBUN BINATANG', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        
        $this->Ln(8);
        $this->SetFont('helvetica', '', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 10, 'Laporan Data Inventaris Hewan', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        
        $this->Ln(15);
        $this->SetDrawColor(40, 167, 69);
        $this->SetLineWidth(0.5);
        $this->Line(15, 30, 282, 30); // Garis untuk landscape
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        
        $tanggal = date('d F Y H:i:s');
        $this->Cell(0, 10, 'Dicetak pada: ' . $tanggal, 0, false, 'L', 0, '', 0, false, 'T', 'M');
        
        $this->Cell(0, 10, 'Halaman ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Zoo Admin');
$pdf->SetTitle('Laporan Data Hewan');
$pdf->SetSubject('Laporan Data Hewan');

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(15, 35, 15);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();

$query = "SELECT * FROM hewan ORDER BY id DESC";
$result = $conn->query($query);

$html = '
<style>
    th {
        background-color: #28a745;
        color: white;
        font-weight: bold;
        text-align: center;
        padding: 5px;
        border: 1px solid #218838;
    }
    td {
        border: 1px solid #dee2e6;
        padding: 5px;
        text-align: left;
    }
    .text-center { text-align: center; }
</style>
<br>
<table cellpadding="4" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="12%">Gambar</th>
            <th width="12%">Kode Hewan</th>
            <th width="17%">Nama Hewan</th>
            <th width="17%">Nama Latin</th>
            <th width="12%">Kategori</th>
            <th width="15%">Habitat</th>
            <th width="10%">Jumlah</th>
        </tr>
    </thead>
    <tbody>';

if ($result->num_rows > 0) {
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        $img_html = '';
        if ($row['gambar'] && file_exists('uploads/' . $row['gambar'])) {
            $ext = strtolower(pathinfo($row['gambar'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                $img_html = '<img src="uploads/' . $row['gambar'] . '" height="40" width="40">';
            } else {
                $img_html = '<small>[Format gambar tidak didukung]</small>';
            }
        } else {
            $img_html = '<small>[Tidak ada gambar]</small>';
        }

        $html .= '<tr>
            <td width="5%" class="text-center">' . $no++ . '</td>
            <td width="12%" class="text-center">' . $img_html . '</td>
            <td width="12%" class="text-center">' . htmlspecialchars($row['kode_hewan']) . '</td>
            <td width="17%">' . htmlspecialchars($row['nama_hewan']) . '</td>
            <td width="17%"><i>' . htmlspecialchars($row['nama_latin']) . '</i></td>
            <td width="12%" class="text-center">' . htmlspecialchars($row['kategori']) . '</td>
            <td width="15%">' . htmlspecialchars($row['habitat']) . '</td>
            <td width="10%" class="text-center">' . htmlspecialchars($row['jumlah']) . ' Ekor</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="8" class="text-center">Belum ada data hewan</td></tr>';
}

$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('Laporan_Data_Hewan.pdf', 'I');
