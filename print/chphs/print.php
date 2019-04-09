<?php
require('chphs.php');

$pdf=new PDF_MC_Table('L','mm','A4');
$pdf->AddPage();
$pdf->AliasNbPages();
$pdf->SetLeftMargin(3); // mao ni sulution para dile ma bugs ang margin left
$pdf->setX(3);

$pdf->SetFont('Arial','B',7);
$pdf->SetWidths(array(
                11, //1
                11, //2
                11, //3
                11, //4
                18, //5
                18.12, //6
                18.12, //7
                11, //8
                18.12, //9
                11, //10
                11, //11
                11, //12
                11, //13
                18.12, //14
                70, //15
                30)); //16
$pdf->Row(array(
                "Cluster No.", //1
                "District No.", //2
                "MLGU No.", //3
                "Brgy No.", //4
                "No.", //5
                "Family Name", //6
                "First Name", //7
                "Middle Initial", //8
                "Date of Birth", //9
                "Gender", //10
                "Weight(kg)", //11
                "Height(cm)", //12
                "Blood Type", //13
                "Contact No", //14
                "Address", //15
                "ID NO" //16
        ),true);

$pdf->SetFont('Arial','',7);
session_start();
$data = $_SESSION['data'];
for($i=0;$i<count($data);$i++){
    $pdf->Row(array($data[$i]["clusterNo"],$data[$i]["districtNo"],$data[$i]["mlguNo"],$data[$i]["brgyNo"],$data[$i]["no"],$data[$i]["lname"],$data[$i]["fname"],
        $data[$i]["mname"],$data[$i]["dob"],$data[$i]["sex"],$data[$i]["weight"],$data[$i]["height"],$data[$i]["bloodType"],$data[$i]["contact_no"],$data[$i]["address"],$data[$i]["chphs_no"]),false);
}
$pdf->Output();