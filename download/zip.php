<?php
require '../config/config.php';

// create csv
$csvFile = fopen ( "images.csv", "a" );
fwrite ( $csvFile, "Title,Image_Name\r\n" );

$images = $db->get ( "images" );
if ($db->count > 0) {
	foreach ( $images as $image ) {
		fwrite ( $csvFile, $image ['image_title'] . "," . $image ['image_name'] . "\r\n" );
	}
}
fclose ( $csvFile );

// create new zip object
$zip = new ZipArchive ();

// create a temp file & open it
$fileName = tempnam ( '.', '' );
$zip->open ( $fileName, ZipArchive::CREATE );

// loop through each image
foreach ( glob ( '../images/' . '*.*' ) as $file ) {
	// add each image to the zip file
	$zip->addFile ( $file, basename ( $file ) );
}
// add csv file to the zip file.
$zip->addFile ( "images.csv" );
// close zip
$zip->close ();

// send the file to the browser as a download
header ( 'Content-disposition: attachment; filename=images.zip' );
header ( 'Content-type: application/zip' );
readfile ( $fileName );
// delete zip file
unlink ( $fileName );
// delete csv file
unlink ( "images.csv" );
?>