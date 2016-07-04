<?php
// this file is requested by ajax request to update views and posts every 15 seconds
require "../config/config.php";
// get number of views and posts
$views = 0; // numbers of views
$posts = 0; // numbers of posts
            
// get number of views from database
$dbresult = $db->get ( "views" );
$views = $dbresult [0] ['views_number'];
// get number of post from database
$dbresult = $db->getOne ( "images", "count(*) as num" );
$posts = $dbresult ['num'];

$data = array (
		"views" => $views,
		"posts" => $posts 
);
echo json_encode ( $data );
?>