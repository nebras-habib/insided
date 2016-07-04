<?php
require '../lib/PHPExcel.php';
require '../config/config.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel ();

// Set document properties
$objPHPExcel->getProperties ()->setCreator ( "nebras" )->setLastModifiedBy ( "nebras" )->setTitle ( "Images" );

// Add header data
$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( 'A1', 'Image Title' )->setCellValue ( 'B1', 'Image Name' );

$rows = 2; // line number in excel file

$images = $db->get ( "images" );

if ($db->count > 0) {
	foreach ( $images as $image ) {
		$Arow = 'A' . $rows;
		$Brow = 'B' . $rows;
		$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValueExplicit ( $Arow, $image ['image_title'], PHPExcel_Cell_DataType::TYPE_STRING )->setCellValueExplicit ( $Brow, $image ['image_name'], PHPExcel_Cell_DataType::TYPE_STRING );
		$rows ++;
	}
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex ( 0 );

header ( 'Content-Type: application/vnd.ms-excel' );
header ( 'Content-Disposition: attachment;filename=images.xls' );

$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
$objWriter->save ( 'php://output' );
?>