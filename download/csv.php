<?php
require '../config/config.php';

$output = fopen ( "php://output", "w" );
fputcsv ( $output, array (
		"Title",
		"Image_Name" 
) );
// get all image from database
$images = $db->get ( "images" );

if ($db->count > 0) {
	foreach ( $images as $image ) {
		fputcsv ( $output, array (
				$image ['image_title'],
				$image ['image_name'] 
		) );
	}
}
fclose ( $output );
header ( 'Content-Type: text/csv' );
header ( 'Content-Disposition: attachment;filename=images.csv' );

?>