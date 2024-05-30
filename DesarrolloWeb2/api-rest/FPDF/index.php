<?php
require('fpdf.php');
require_once ('../modelos/conexion.php');
 
class PDF extends FPDF {
    // Cabecera de página
    function Header() {
        $this->SetFont('Arial','B',10);
        $this->Cell(0,10,mb_convert_encoding('Citas', 'ISO-8859-1', 'UTF-8'),0,1,'C');
        $this->Ln(10);
    }
 
    // Pie de página
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,mb_convert_encoding('Página '.$this->PageNo().'/{nb}', 'ISO-8859-1', 'UTF-8'),0,0,'C');
    }
 
    // Cargar datos
    function LoadData() {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT  c.Fecha_Cita, p.Nombre AS NombrePaciente, 
                m.Nombre AS NombreMedico, c.No_Consultorio
                FROM citas c
                INNER JOIN pacientes p ON c.IdPaciente = p.Id
                INNER JOIN medicos m ON c.IdMedico = m.Id");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
        // Formatear la fecha a día/mes/año
        foreach ($data as &$row) {
            $row['Fecha_Cita'] = date('d/m/Y', strtotime($row['Fecha_Cita']));
        }
 
        return $data;
    }
 
    // Tabla
    function BasicTable($header, $data) {
        // Cabecera
        foreach($header as $col) {
            $this->Cell(40,7,mb_convert_encoding($col, 'ISO-8859-1', 'UTF-8'),1);
        }
        $this->Ln();
        // Datos
        foreach($data as $row) {
            foreach($row as $col) {
                $this->Cell(40,6,mb_convert_encoding($col, 'ISO-8859-1', 'UTF-8'),1);
            }
            $this->Ln();
        }
    }
}
 
// Creación del objeto de la clase heredada
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$header = array('Fecha de Cita', 'Nombre del Paciente', 'Médico', 'Consultorio');
$data = $pdf->LoadData();
$pdf->BasicTable($header, $data);
$pdf->Output();
?>